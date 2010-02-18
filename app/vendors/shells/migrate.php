
<?php
/**
 * The MigrateTask runs your database migrations to the specified scheam version.
 * If no version is specified, migrations are run to the latest version.
 *
 * PHP versions 4 and 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2006-2007, Joel Moss
 * @link				http://joelmoss.info
 * @package			cake
 * @subpackage		cake.cake.scripts.bake
 * @since			CakePHP(tm) v 1.2
 * @version			$Version: 1.0 $
 * @modifiedby		$LastChangedBy: joelmoss $
 * @lastmodified	$Date: 2007-02-16 09:09:45 +0000 (Fri, 16 Feb 2007) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
 * 
 * @Changelog (started as of v3.0)
 * 
 * v 3.2
 *  [+] refactored (again!) to work with the new Cake console
 *  [+] default type is now 'string', which means that a 'text(255)' column can be created without specifying the type
 *  [+] can now specify 'fkey' or 'fkeys' as a column name, then passing one or more foreign key names
 *        Example:
 *          fkey: user    # an integer column will be created called 'user_id'
 *        or
 *          fkeys: [user, group]    # two integer columns will be created called 'user_id' and 'group_id'
 * v 3.1
 *  [+] can now parse and run PHP code within migration files
 *  [+] added string type. any column with a type of 'string' without a length set will default to varchar(255)
 *  [+] automatically detects any column name ending with '_id' as an integer
 * v 3.0
 *  [+] refactored as a bake2 task compatible with CakePHP 1.2
 * 
*/

uses('file', 'folder');

class MigrateShell extends Shell
{
  var $dataSource = 'default';
  var $db;

  function initialize()
  {
   	   $this->welcome();
		
		$this->hr();
		$this->out('App: '. APP_DIR);
		$this->out('Path: '. ROOT . DS . APP_DIR);
		define('FIXTURES_PATH', APP_PATH .'config' .DS. 'fixtures');
		define('MIGRATIONS_PATH', APP_PATH .'config' .DS. 'migrations');
		$this->out('Migrations path: ' );
		$this->getMigrations();
		$this->hr();

		$this->initDatabase($this->dataSource);
		
		
		$this->getMigrationVersion();
		
  }
  
	function main()
	{
    $this->to_version = isset($this->params['v']) ? $this->params['v'] : $this->migration_count;

    if ($this->to_version === 'reset')
    {
      $this->reset();
      exit;
    }
    
    $this->run();
	}

  /**
   * Reset migration version to zero without running migrations up or down
   */
  	function reset()
  	{
      $this->hr();
      $this->out('');
  		$this->out('Resetting Migrations:');

  		$tables = $this->_db->listTables();
  		foreach ($tables as $table)
  		{
  			if ($table == 'schema_info' || $table == CAKE_SESSION_TABLE) continue;
  			$r = $this->_db->dropTable($table);
  			if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  			$this->out('');
  			$this->out('  Table \''.$table.'\' have been dropped.');
  		}

  		$r = $this->_db->exec("UPDATE `schema_info` SET version=0");
  		if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  		$this->out('');
  		$this->out('  Database schema reset to zero.');
  		$this->hr();
  		exit;
  	}

  	function run()
  	{
  		$this->out('yoooooo');
  		$this->hr();
  		if ($this->migration_count === 0)
  		{
  			$this->out('');
  			$this->out('  ** No migrations found **');
  			$this->out('');
  			$this->hr();
  			$this->out('');
  			exit;
  		}

  		$new_version = $this->to_version;

  		if (!is_numeric($new_version))
  		{
  			$this->out('');
  			$this->out('  ** Migration version number ('.$new_version.') is invalid. **');
  			$this->out('');
  			$this->hr();
  			$this->out('');
  			exit;
  		}
  		if ($new_version > $this->migration_count)
  		{
  			$this->out('');
  			$this->out('  ** Version number entered ('.$new_version.') does not exist. **');
  			$this->out('');
  			$this->hr();
  			$this->out('');
  			exit;
  		}
  		if ($this->current_version == $new_version)
  		{
  			$this->out('');
  			$this->out('  ** Migrations are up to date **');
  			$this->out('');
  			$this->hr();
  			$this->out('');
  			exit;
  		}

  		$direction = ($new_version < $this->current_version) ? 'down' : 'up';
  		if ($direction == 'down') usort($this->migrations, array($this, '_downMigrations'));

  		$this->out('');
  		$this->out("  Migrating database $direction from version {$this->current_version} to $new_version ...");
  		$this->out('');

  		foreach($this->migrations as $migration_name)
  		{
  			preg_match("/^([0-9]+)\_(.+)$/", $migration_name, $match);
  			$num = $match[1];
  			$name = $match[2];

  			if ($direction == 'up')
  			{
  				if ($num <= $this->current_version) continue;
  				if ($num > $new_version) break;
  			}
  			else
  			{
  				if ($num > $this->current_version) continue;
  				if ($num == $new_version) break;
  			}

  			$this->out("     [$num] $name ... ", false);

  			$this->running_migration_name = $migration_name;

  			$res = $this->startMigration(MIGRATIONS_PATH . '/' . $migration_name, $direction);
  			if ($res == 1)
  			{
  				$this->out('Complete.');
  				$this->out('');
  				if ($direction == 'up')
  				{
  					$r = $this->_db->exec("UPDATE `schema_info` SET version=version+1");
  					if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  				}
  				else
  				{
  					$r = $this->_db->exec("UPDATE `schema_info` SET version=version-1");
  					if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  				}
  			}
  			else
  			{
  				$this->out("  ERROR: $res");
  				$this->hr();
  				exit;
  			}
  		}

  		$this->out('  Migrations completed.');
  		$this->out('');
  		$this->hr();
  		$this->out('');
  		exit;
  	}

  	function startMigration($file, $direction)
  	{
  		$yml = $this->_parsePhp($file);

  		if (function_exists('syck_load'))
  		{
  			$array = @syck_load($yml);
  		}
  		else
  		{
  			vendor('Spyc');
  			$array = Spyc::YAMLLoad($yml);
  		}

  		if (!is_array($array)) return "Unable to parse YAML Migration file";
  		if (!$array[up($direction)]) return "Direction does not exist!";
  		return $this->_array_to_sql($array[up($direction)]);
  	}

  	function _array_to_sql($array)
  	{
  	  foreach ($array as $name=>$action)
  		{
  			if ($name == 'create_table' || $name == 'create_tables')
  			{
  				/*
  				 * Valid fields: text, string, integer, blob, boolean, float, date, time, timestamp(datetime)
  				 * Read: http://cvs.php.net/viewcvs.cgi/pear/MDB2/docs/datatypes.html?view=co
  				 */
  				foreach ($action as $table=>$fields)
  				{
  					$rfields = array();
  					$indexes = array();
  					$uniques = array();
  					$pk = array();
  					if (!isset($fields['no_id']))
  					{
  						$rfields['id']['type'] = 'integer';
  						$rfields['id']['notnull'] = true;
  						$rfields['id']['autoincrement'] = true;
  					}
  					foreach ($fields as $field=>$props)
  					{
  						if($field == 'no_id' || $field == 'created' || $field == 'modified' || $field == 'no_dates' || $field == 'fkey' || $field == 'fkeys') continue;

  						if (preg_match("/\\_id$/", $field) && count($props) < 1)
  						{
  						  $rfields[$field]['type'] = 'integer';
  						  continue;
  						}

              $props['type'] = isset($props['type']) ? $props['type'] : 'string';
  						$rfields[$field]['type'] = $props['type'];
  						if ($props['type'] == 'string')
  						{
  						  $rfields[$field]['type'] = 'text';
  						  if (!isset($props['length'])) $rfields[$field]['length'] = 255;
  					  }

  						if (isset($props['length']))
  							$rfields[$field]['length'] = $props['length'];

  						if (isset($props['notnull']))
  						  $rfields[$field]['notnull'] = $props['notnull'] ? true : false;

  						if (isset($props['default']))
  							$rfields[$field]['default'] = $props['default'];

  						if (isset($props['index'])) $indexes[] = $field;
  						if (isset($props['unique'])) $uniques[] = $field;
  						if (isset($props['primary'])) $pk[$field] = '';
  					}

            if (!isset($fields['created'])) $fields['created'] = null;
            if (!isset($fields['no_dates'])) $fields['no_dates'] = null;
            if (!isset($fields['modified'])) $fields['modified'] = null;
            
  					if ($fields['created'] !== false && $fields['no_dates'] !== true)
  					{
  						$rfields['created']['type'] = 'timestamp';
  						$rfields['created']['notnull'] = false;
  						$rfields['created']['default'] = NULL;
  					}
  					if ($fields['modified'] !== false && $fields['no_dates'] !== true)
  					{
  						$rfields['modified']['type'] = 'timestamp';
  						$rfields['modified']['notnull'] = false;
  						$rfields['modified']['default'] = NULL;
  					}
  					
  					if (isset($fields['fkey']))
  					{
						  $rfields[$fields['fkey'].'_id']['type'] = 'integer';
  					}
  					if (isset($fields['fkeys']))
  					{
						  foreach($fields['fkeys'] as $key)
						  {
						    $rfields[$key.'_id']['type'] = 'integer';
					    }
  					}

  					$r = $this->_db->createTable($table, $rfields, array('primary'=>$pk));
  					if (PEAR::isError($r)) $this->err($r->getUserInfo());
  					if (count($indexes) > 0)
  					{
  						foreach ($indexes as $field)
  						{
  							$r = $this->_db->createIndex($table, $field, array(
  								'fields'=>
  									array($field=>array())
  							));
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  						}
  					}
  					if (count($uniques) > 0)
  					{
  						foreach ($uniques as $field)
  						{
  							$r = $this->_db->createConstraint($table, $field, array(
  								'unique'=>true,
  								'fields'=>
  									array($field=>array())
  							));
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  						}
  					}
  				}
  			}
  			elseif ($name == 'drop_table' || $name == 'drop_tables')
  			{
  				if (is_array($action))
  				{
  					foreach ($action as $table)
  					{
  						$r = $this->_db->dropTable($table);
  						if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					}
  				}
  				else
  				{
  					$r = $this->_db->dropTable($action);
  					if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  				}
  			}
  			elseif ($name == 'add_fields' || $name == 'add_field')
  			{
  				/*
  				 * Valid fields: text, integer, blob, boolean, float, date, time, timestamp(datetime)
  				 * Read: http://cvs.php.net/viewcvs.cgi/pear/MDB2/docs/datatypes.html?view=co
  				 */
  				foreach ($action as $table=>$fields)
  				{
  					$rfields = array();
  					$indexes = array();
  					$uniques = array();
  					$pk = array();
  					foreach ($fields as $field=>$props)
  					{
  						if ($field == 'created' || $field == 'modified')
  						{
  							$rfields[$field]['type'] = 'timestamp';
  							$rfields[$field]['notnull'] = false;
  							$rfields[$field]['default'] = NULL;
  						}
  						else
  						{
    						if (preg_match("/\\_id$/", $field) && count($props) < 1)
    						{
    						  $rfields[$field]['type'] = 'integer';
    						  continue;
    						}

    						$rfields[$field]['type'] = $props['type'];
    						if ($props['type'] == 'string')
    						{
    						  $rfields[$field]['type'] = 'text';
    						  if (!$props['length']) $rfields[$field]['length'] = 255;
    					  }

  							if ($props['type'] == 'text' && !$props['length'])
  								$rfields[$field]['length'] = 255;

  							if (isset($props['length']))
  								$rfields[$field]['length'] = $props['length'];

  							$rfields[$field]['notnull'] = $props['notnull'] ? true : false;

  							if (isset($props['default']))
  								$rfields[$field]['default'] = $props['default'];

  							if ($props['index']) $indexes[] = $field;
  							if ($props['unique']) $uniques[] = $field;
  							if ($props['primary_key']) $pk = $field;
  						}
  					}
  					
  					if (isset($fields['fkey']))
  					{
						  $rfields[$fields['fkey'].'_id']['type'] = 'integer';
  					}
  					if (isset($fields['fkeys']))
  					{
						  foreach($fields['fkeys'] as $key)
						  {
						    $rfields[$key.'_id']['type'] = 'integer';
					    }
  					}

  					$r = $this->_db->alterTable($table, array('add'=>$rfields), false);
  					if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					if ($pk)
  					{
  						$r = $this->_db->createConstraint($table, $pk, array(
  							'primary'=>true,
  							'fields'=>
  								array($pk=>array())
  						));
  						if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					}
  					if (count($indexes) > 0)
  					{
  						foreach ($indexes as $field)
  						{
  							$r = $this->_db->createIndex($table, $field, array(
  								'fields'=>
  									array($field=>array())
  							));
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  						}
  					}
  					if (count($uniques) > 0)
  					{
  						foreach ($uniques as $field)
  						{
  							$r = $this->_db->createConstraint($table, $field, array(
  								'unique'=>true,
  								'fields'=>
  									array($field=>array())
  							));
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  						}
  					}
  				}
  			}
  			elseif ($name == 'drop_fields' || $name == 'drop_field')
  			{
  				foreach ($action as $table=>$fields)
  				{
  					if (is_array($fields))
  					{
  						foreach($fields as $nil=>$field) $rfields[$field] = array();
  						$r = $this->_db->alterTable($table, array('remove'=>$rfields), false);
  						if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					}
  					else
  					{
  						$r = $this->_db->alterTable($table, array('remove'=>array($fields=>array())), false);
  						if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					}
  				}
  			}
  			elseif ($name == 'alter_field' || $name == 'alter_fields')
  			{
  				/*
  				 * Valid fields: text, integer, blob, boolean, float, date, time, timestamp(datetime)
  				 * Read: http://cvs.php.net/viewcvs.cgi/pear/MDB2/docs/datatypes.html?view=co
  				 */
  				foreach ($action as $table=>$fields)
  				{
  					$change = array();
  					$indexes = array();
  					$uniques = array();
  					$pk = null;
  					foreach($fields as $field=>$props)
  					{
  						if (!isset($props['type']))
  						{
  							$r = $this->_db->getTableFieldDefinition($table, $field);
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  							$props['type'] = $r[0]['mdb2type'];
  							if (!isset($props['length'])) $props['length'] = $r[0]['length'];
  						}
  						if ($props['index'] === true) $indexes[] = $field;
  						if ($props['unique'] === true) $uniques[] = $field;
  						if ($props['primary'] === true) $pk = $field;
  						if ($props['index'] === false) $Nindexes[] = $field;
  						if ($props['unique'] === false) $Nuniques[] = $field;
  						if ($props['primary_key'] === false) $Npk = $field;
  						unset($props['index'], $props['unique'], $props['primary']);
  						$change[$field]['definition'] = $props;
  					}
  					$r = $this->_db->alterTable($table, array('change'=>$change), false);
  					if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					if ($Npk)
  					{
  						$r = $this->_db->dropConstraint($table, $Npk, true);
  						if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					}
  					if ($pk)
  					{
  						$r = $this->_db->createConstraint($table, $pk, array(
  							'primary'=>true,
  							'fields'=>
  								array($pk=>array())
  						));
  						if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					}
  					if (count($Nindexes) > 0)
  					{
  						foreach ($Nindexes as $field)
  						{
  							$r = $this->_db->dropIndex($table, $field);
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  						}
  					}
  					if (count($indexes) > 0)
  					{
  						foreach ($indexes as $field)
  						{
  							$r = $this->_db->createIndex($table, $field, array(
  								'fields'=>
  									array($field=>array())
  							));
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  						}
  					}
  					if (count($Nuniques) > 0)
  					{
  						foreach ($Nuniques as $field)
  						{
  							$r = $this->_db->dropConstraint($table, $field);
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  						}
  					}
  					if (count($uniques) > 0)
  					{
  						foreach ($uniques as $field)
  						{
  							$r = $this->_db->createConstraint($table, $field, array(
  								'unique'=>true,
  								'fields'=>
  									array($field=>array())
  							));
  							if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  						}
  					}
  				}
  			}
  			elseif ($name == 'query' || $name == 'queries')
  			{
  				if (is_array($action))
  				{
  					foreach ($action as $sql)
  					{
  						$r = $this->_db->query($sql);
  						if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  					}
  				}
  				else
  				{
  					$r = $this->_db->query($action);
  					if (PEAR::isError($r)) $this->err($r->getDebugInfo());
  				}
  			}
  		}
  		return 1;
  	}

  	function _upMigrations($a, $b)
  	{
  		list($aStr) = explode('_', $a);
  		list($bStr) = explode('_', $b);
  		$aNum = (int)$aStr;
  		$bNum = (int)$bStr;
  		if ($aNum == $bNum) {
  			return 0;
  		}
  		return ($aNum > $bNum) ? 1 : -1;
  	}

  	function _downMigrations($a, $b)
  	{
  		list($aStr) = explode('_', $a);
  		list($bStr) = explode('_', $b);
  		$aNum = (int)$aStr;
  		$bNum = (int)$bStr;
  		if ($aNum == $bNum) {
  			return 0;
  		}
  		return ($aNum > $bNum) ? -1 : 1;
  	}
	
  	function initDatabase()
  	{
  		if (!include_once('MDB2.php'))
  		{
  			$this->err("Unable to include PEAR.php and MDB2.php\n");
  			exit;
  		}

  		if (!config('database')) {
  			$this->out('');
  			$this->out('Your database configuration was not found. Take a moment to create/edit your APP/config/database.php file.');
  			$this->out('');
  			$this->out('');
  			exit;
  		}

  		if (isset($this->params['datasource'])) {
  			$this->dataSource = $this->params['datasource'];
  		}
  		
  		$ds = new DATABASE_CONFIG();
  		$config = $ds->{$this->dataSource};
  		$dsn = array(
  		    'phptype'	=>	$config['driver'],
  		    'username'	=>	$config['login'],
  		    'password'	=>	$config['password'],
  		    'hostspec'	=>	$config['host'],
  		    'database'	=>	$config['database']
  		);
  		$options = array(
  			'debug' 		=>	'DEBUG',
  			'portability'	=>	'DB_PORTABILITY_ALL'
  		);
  		$this->_db = &MDB2::connect($dsn, $options);
  		if (PEAR::isError($this->_db)) $this->err($this->_db->getDebugInfo());
  		$this->_db->setFetchMode(MDB2_FETCHMODE_ASSOC);
  		$this->_db->loadModule('Manager');
  		$this->_db->loadModule('Extended');
  		$this->_db->loadModule('Reverse');	
  	}
	
  	function getMigrationVersion()
  	{
  		$r = $tables = $this->_db->listTables();
  		
  		if (PEAR::isError($r)) $this->err($r->getMessage());

  		if (!in_array('schema_info', $tables))
  		{
  			$this->out('Creating migrations version table (\'schema_info\') ...', false);

  			$this->_db->createTable('schema_info', array(
  				'version'	=>	array(
  					'type'		=>	'integer',
  					'unsigned'	=>	1,
  					'notnull'	=>	1,
  					'default'	=>	0
  				)
  			));
  			$r = $this->_db->autoExecute('schema_info', array('version'=>0), MDB2_AUTOQUERY_INSERT, null, array('integer'));
  			if (PEAR::isError($r)) $this->err($r->getDebugInfo());

  			$this->out('CREATED!');
  		}

  		$version = $this->_db->queryOne("SELECT version FROM schema_info");
  		$this->current_version = $version;
  		settype($this->current_version, 'integer');

  		$this->out('Current schema version: '.$this->current_version);
  	}

  	function getMigrations()
  	{
  		$folder = new Folder(MIGRATIONS_PATH, true, 0777);
  		
  		$this->migrations = $folder->find("[0-9]+_.+\.yml");
  		print_r($this->migrations);
  		usort($this->migrations, array($this, '_upMigrations'));
  		$this->migration_count = count($this->migrations);
  	}

  	function _parsePhp($file)
  	{
  		ob_start();
  		include ($file);
  		$buf = ob_get_contents();
  		ob_end_clean();
  		return $buf;
  	}
	
	function welcome()
	{
		$this->out('');
    $this->out(' __  __  _  _  __     ___     __   _   __  ___    __        _ ');
    $this->out('|   |__| |_/  |__    | | | | | _  |_| |__|  |  | |  | |\ | |_ ');
    $this->out('|__ |  | | \_ |__    | | | | |__| | \ |  |  |  | |__| | \|  _|');
		$this->out('');
		
	}
  
}

?>