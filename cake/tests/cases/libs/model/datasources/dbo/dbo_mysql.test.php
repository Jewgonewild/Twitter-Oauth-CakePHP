<?php
/* SVN FILE: $Id$ */
/**
 * DboMysqlTest file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs
 * @since         CakePHP(tm) v 1.2.0
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', array('Model', 'DataSource', 'DboSource', 'DboMysql'));

Mock::generatePartial('DboMysql', 'QueryMockDboMysql', array('query'));
/**
 * DboMysqlTestDb class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.datasources
 */
class DboMysqlTestDb extends DboMysql {
/**
 * simulated property
 *
 * @var array
 * @access public
 */
	var $simulated = array();
/**
 * testing property
 *
 * @var bool true
 * @access public
 */
	var $testing = true;
/**
 * execute method
 *
 * @param mixed $sql
 * @access protected
 * @return void
 */
	function _execute($sql) {
		if ($this->testing) {
			$this->simulated[] = $sql;
			return null;
		}
		return parent::_execute($sql);
	}
/**
 * getLastQuery method
 *
 * @access public
 * @return void
 */
	function getLastQuery() {
		return $this->simulated[count($this->simulated) - 1];
	}
}
/**
 * MysqlTestModel class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.datasources
 */
class MysqlTestModel extends Model {
/**
 * name property
 *
 * @var string 'MysqlTestModel'
 * @access public
 */
	var $name = 'MysqlTestModel';
/**
 * useTable property
 *
 * @var bool false
 * @access public
 */
	var $useTable = false;
/**
 * find method
 *
 * @param mixed $conditions
 * @param mixed $fields
 * @param mixed $order
 * @param mixed $recursive
 * @access public
 * @return void
 */
	function find($conditions = null, $fields = null, $order = null, $recursive = null) {
		return $conditions;
	}
/**
 * findAll method
 *
 * @param mixed $conditions
 * @param mixed $fields
 * @param mixed $order
 * @param mixed $recursive
 * @access public
 * @return void
 */
	function findAll($conditions = null, $fields = null, $order = null, $recursive = null) {
		return $conditions;
	}
/**
 * schema method
 *
 * @access public
 * @return void
 */
	function schema() {
		return array(
			'id'		=> array('type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'),
			'client_id'	=> array('type' => 'integer', 'null' => '', 'default' => '0', 'length' => '11'),
			'name'		=> array('type' => 'string', 'null' => '', 'default' => '', 'length' => '255'),
			'login'		=> array('type' => 'string', 'null' => '', 'default' => '', 'length' => '255'),
			'passwd'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'),
			'addr_1'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'),
			'addr_2'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '25'),
			'zip_code'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'city'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'country'	=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'phone'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'fax'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'url'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'),
			'email'		=> array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'),
			'comments'	=> array('type' => 'text', 'null' => '1', 'default' => '', 'length' => ''),
			'last_login'=> array('type' => 'datetime', 'null' => '1', 'default' => '', 'length' => ''),
			'created'	=> array('type' => 'date', 'null' => '1', 'default' => '', 'length' => ''),
			'updated'	=> array('type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null)
		);
	}
}
/**
 * DboMysqlTest class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.datasources.dbo
 */
class DboMysqlTest extends CakeTestCase {
/**
 * The Dbo instance to be tested
 *
 * @var DboSource
 * @access public
 */
	var $Db = null;
/**
 * Skip if cannot connect to mysql
 *
 * @access public
 */
	function skip() {
		$this->_initDb();
		$this->skipUnless($this->db->config['driver'] == 'mysql', '%s MySQL connection not available');
	}
/**
 * Sets up a Dbo class instance for testing
 *
 * @access public
 */
	function setUp() {
		$db = ConnectionManager::getDataSource('test_suite');
		$this->db = new DboMysqlTestDb($db->config);
		$this->model = new MysqlTestModel();
	}
/**
 * Sets up a Dbo class instance for testing
 *
 * @access public
 */
	function tearDown() {
		unset($this->db);
	}
/**
 * startCase
 *
 * @return void
 **/
	function startCase() {
		$this->_debug = Configure::read('debug');
		Configure::write('debug', 1);
	}
/**
 * endCase
 *
 * @return void
 **/
	function endCase() {
		Configure::write('debug', $this->_debug);
	}
/**
 * Test Dbo value method
 *
 * @access public
 */
	function testQuoting() {
		$result = $this->db->fields($this->model);
		$expected = array(
			'`MysqlTestModel`.`id`',
			'`MysqlTestModel`.`client_id`',
			'`MysqlTestModel`.`name`',
			'`MysqlTestModel`.`login`',
			'`MysqlTestModel`.`passwd`',
			'`MysqlTestModel`.`addr_1`',
			'`MysqlTestModel`.`addr_2`',
			'`MysqlTestModel`.`zip_code`',
			'`MysqlTestModel`.`city`',
			'`MysqlTestModel`.`country`',
			'`MysqlTestModel`.`phone`',
			'`MysqlTestModel`.`fax`',
			'`MysqlTestModel`.`url`',
			'`MysqlTestModel`.`email`',
			'`MysqlTestModel`.`comments`',
			'`MysqlTestModel`.`last_login`',
			'`MysqlTestModel`.`created`',
			'`MysqlTestModel`.`updated`'
		);
		$this->assertEqual($result, $expected);

		$expected = 1.2;
		$result = $this->db->value(1.2, 'float');
		$this->assertEqual($expected, $result);

		$expected = "'1,2'";
		$result = $this->db->value('1,2', 'float');
		$this->assertEqual($expected, $result);

		$expected = "'4713e29446'";
		$result = $this->db->value('4713e29446');

		$this->assertEqual($expected, $result);

		$expected = 'NULL';
		$result = $this->db->value('', 'integer');
		$this->assertEqual($expected, $result);

		$expected = 'NULL';
		$result = $this->db->value('', 'boolean');
		$this->assertEqual($expected, $result);

		$expected = 10010001;
		$result = $this->db->value(10010001);
		$this->assertEqual($expected, $result);

		$expected = "'00010010001'";
		$result = $this->db->value('00010010001');
		$this->assertEqual($expected, $result);
	}
/**
 * testTinyintCasting method
 *
 * @access public
 * @return void
 */
	function testTinyintCasting() {
		$this->db->cacheSources = $this->db->testing = false;
		$this->db->query('CREATE TABLE ' . $this->db->fullTableName('tinyint') . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');

		$this->model = new CakeTestModel(array(
			'name' => 'Tinyint', 'table' => $this->db->fullTableName('tinyint', false)
		));

		$result = $this->model->schema();
		$this->assertEqual($result['bool']['type'], 'boolean');
		$this->assertEqual($result['small_int']['type'], 'integer');

		$this->assertTrue($this->model->save(array('bool' => 5, 'small_int' => 5)));
		$result = $this->model->find('first');
		$this->assertIdentical($result['Tinyint']['bool'], '1');
		$this->assertIdentical($result['Tinyint']['small_int'], '5');
		$this->model->deleteAll(true);

		$this->assertTrue($this->model->save(array('bool' => 0, 'small_int' => 100)));
		$result = $this->model->find('first');
		$this->assertIdentical($result['Tinyint']['bool'], '0');
		$this->assertIdentical($result['Tinyint']['small_int'], '100');
		$this->model->deleteAll(true);

		$this->assertTrue($this->model->save(array('bool' => true, 'small_int' => 0)));
		$result = $this->model->find('first');
		$this->assertIdentical($result['Tinyint']['bool'], '1');
		$this->assertIdentical($result['Tinyint']['small_int'], '0');
		$this->model->deleteAll(true);

		$this->db->query('DROP TABLE ' . $this->db->fullTableName('tinyint'));
	}
/**
 * testIndexDetection method
 *
 * @return void
 * @access public
 */
	function testIndexDetection() {
		$this->db->cacheSources = $this->db->testing = false;

		$name = $this->db->fullTableName('simple');
		$this->db->query('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');
		$expected = array('PRIMARY' => array('column' => 'id', 'unique' => 1));
		$result = $this->db->index($name, false);
		$this->assertEqual($expected, $result);
		$this->db->query('DROP TABLE ' . $name);

		$name = $this->db->fullTableName('with_a_key');
		$this->db->query('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
		);
		$result = $this->db->index($name, false);
		$this->assertEqual($expected, $result);
		$this->db->query('DROP TABLE ' . $name);

		$name = $this->db->fullTableName('with_two_keys');
		$this->db->query('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
			'pointless_small_int' => array('column' => 'small_int', 'unique' => 0),
		);
		$result = $this->db->index($name, false);
		$this->assertEqual($expected, $result);
		$this->db->query('DROP TABLE ' . $name);

		$name = $this->db->fullTableName('with_compound_keys');
		$this->db->query('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ), KEY `one_way` ( `bool`, `small_int` ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
			'pointless_small_int' => array('column' => 'small_int', 'unique' => 0),
			'one_way' => array('column' => array('bool', 'small_int'), 'unique' => 0),
		);
		$result = $this->db->index($name, false);
		$this->assertEqual($expected, $result);
		$this->db->query('DROP TABLE ' . $name);

		$name = $this->db->fullTableName('with_multiple_compound_keys');
		$this->db->query('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ), KEY `one_way` ( `bool`, `small_int` ), KEY `other_way` ( `small_int`, `bool` ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
			'pointless_small_int' => array('column' => 'small_int', 'unique' => 0),
			'one_way' => array('column' => array('bool', 'small_int'), 'unique' => 0),
			'other_way' => array('column' => array('small_int', 'bool'), 'unique' => 0),
		);
		$result = $this->db->index($name, false);
		$this->assertEqual($expected, $result);
		$this->db->query('DROP TABLE ' . $name);
	}
/**
 * MySQL 4.x returns index data in a different format,
 * Using a mock ensure that MySQL 4.x output is properly parsed.
 *
 * @return void
 **/
	function testIndexOnMySQL4Output() {
		$name = $this->db->fullTableName('simple');

		$mockDbo =& new QueryMockDboMysql($this);
		$columnData = array(
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '0',
				'Key_name' => 'PRIMARY',
				'Seq_in_index' => '1',
				'Column_name' => 'id',
				'Collation' => 'A',
				'Cardinality' => '0',
				'Sub_part' => NULL,
				'Packed' => NULL,
				'Null' => '',
				'Index_type' => 'BTREE',
				'Comment' => ''
			)),
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '1',
				'Key_name' => 'pointless_bool',
				'Seq_in_index' => '1',
				'Column_name' => 'bool',
				'Collation' => 'A',
				'Cardinality' => NULL,
				'Sub_part' => NULL,
				'Packed' => NULL,
				'Null' => 'YES',
				'Index_type' => 'BTREE',
				'Comment' => ''
			)),
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '1',
				'Key_name' => 'pointless_small_int',
				'Seq_in_index' => '1',
				'Column_name' => 'small_int',
				'Collation' => 'A',
				'Cardinality' => NULL,
				'Sub_part' => NULL,
				'Packed' => NULL,
				'Null' => 'YES',
				'Index_type' => 'BTREE',
				'Comment' => ''
			)),
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '1',
				'Key_name' => 'one_way',
				'Seq_in_index' => '1',
				'Column_name' => 'bool',
				'Collation' => 'A',
				'Cardinality' => NULL,
				'Sub_part' => NULL,
				'Packed' => NULL,
				'Null' => 'YES',
				'Index_type' => 'BTREE',
				'Comment' => ''
			)),
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '1',
				'Key_name' => 'one_way',
				'Seq_in_index' => '2',
				'Column_name' => 'small_int',
				'Collation' => 'A',
				'Cardinality' => NULL,
				'Sub_part' => NULL,
				'Packed' => NULL,
				'Null' => 'YES',
				'Index_type' => 'BTREE',
				'Comment' => ''
			))
		);
		$mockDbo->setReturnValue('query', $columnData, array('SHOW INDEX FROM ' . $name));

		$result = $mockDbo->index($name, false);
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
			'pointless_small_int' => array('column' => 'small_int', 'unique' => 0),
			'one_way' => array('column' => array('bool', 'small_int'), 'unique' => 0),
		);
		$this->assertEqual($result, $expected);
	}
/**
 * testColumn method
 *
 * @return void
 * @access public
 */
	function testColumn() {
		$result = $this->db->column('varchar(50)');
		$expected = 'string';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('text');
		$expected = 'text';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('int(11)');
		$expected = 'integer';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('int(11) unsigned');
		$expected = 'integer';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('tinyint(1)');
		$expected = 'boolean';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('boolean');
		$expected = 'boolean';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('float');
		$expected = 'float';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('float unsigned');
		$expected = 'float';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('double unsigned');
		$expected = 'float';
		$this->assertEqual($result, $expected);

		$result = $this->db->column('decimal(14,7) unsigned');
		$expected = 'float';
		$this->assertEqual($result, $expected);
	}
/**
 * testAlterSchemaIndexes method
 *
 * @access public
 * @return void
 */
	function testAlterSchemaIndexes() {
		App::import('Core', 'Schema');
		$this->db->cacheSources = $this->db->testing = false;

		$schema1 =& new CakeSchema(array(
			'name' => 'AlterTest1',
			'connection' => 'test_suite',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
				'group1' => array('type' => 'integer', 'null' => true),
				'group2' => array('type' => 'integer', 'null' => true)
		)));
		$this->db->query($this->db->createSchema($schema1));

		$schema2 =& new CakeSchema(array(
			'name' => 'AlterTest2',
			'connection' => 'test_suite',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
				'group1' => array('type' => 'integer', 'null' => true),
				'group2' => array('type' => 'integer', 'null' => true),
				'indexes' => array(
					'name_idx' => array('column' => 'name', 'unique' => 0),
					'group_idx' => array('column' => 'group1', 'unique' => 0),
					'compound_idx' => array('column' => array('group1', 'group2'), 'unique' => 0),
					'PRIMARY' => array('column' => 'id', 'unique' => 1))
		)));
		$this->db->query($this->db->alterSchema($schema2->compare($schema1)));

		$indexes = $this->db->index('altertest');
		$this->assertEqual($schema2->tables['altertest']['indexes'], $indexes);

		// Change three indexes, delete one and add another one
		$schema3 =& new CakeSchema(array(
			'name' => 'AlterTest3',
			'connection' => 'test_suite',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
				'group1' => array('type' => 'integer', 'null' => true),
				'group2' => array('type' => 'integer', 'null' => true),
				'indexes' => array(
					'name_idx' => array('column' => 'name', 'unique' => 1),
					'group_idx' => array('column' => 'group2', 'unique' => 0),
					'compound_idx' => array('column' => array('group2', 'group1'), 'unique' => 0),
					'id_name_idx' => array('column' => array('id', 'name'), 'unique' => 0))
		)));

		$this->db->query($this->db->alterSchema($schema3->compare($schema2)));

		$indexes = $this->db->index('altertest');
		$this->assertEqual($schema3->tables['altertest']['indexes'], $indexes);

		// Compare us to ourself.
		$this->assertEqual($schema3->compare($schema3), array());

		// Drop the indexes
		$this->db->query($this->db->alterSchema($schema1->compare($schema3)));

		$indexes = $this->db->index('altertest');
		$this->assertEqual(array(), $indexes);

		$this->db->query($this->db->dropSchema($schema1));
	}
}
?>