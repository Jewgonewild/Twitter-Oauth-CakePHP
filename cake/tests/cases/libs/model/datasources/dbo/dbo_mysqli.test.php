<?php
/* SVN FILE: $Id$ */
/**
 * DboMysqliTest file
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
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
	define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}
App::import('Core', array('Model', 'DataSource', 'DboSource', 'DboMysqli'));
/**
 * DboMysqliTestDb class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.datasources
 */
class DboMysqliTestDb extends DboMysqli {
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
 * MysqliTestModel class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.datasources
 */
class MysqliTestModel extends Model {
/**
 * name property
 *
 * @var string 'MysqlTestModel'
 * @access public
 */
	var $name = 'MysqliTestModel';
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
 * DboMysqliTest class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.datasources.dbo
 */
class DboMysqliTest extends CakeTestCase {
/**
 * The Dbo instance to be tested
 *
 * @var DboSource
 * @access public
 */
	var $Db = null;
/**
 * Skip if cannot connect to mysqli
 *
 * @access public
 */
	function skip() {
		$this->_initDb();
		$this->skipUnless($this->db->config['driver'] == 'mysqli', '%s MySQLi connection not available');
	}
/**
 * Sets up a Dbo class instance for testing
 *
 * @access public
 */
	function setUp() {
		$db = ConnectionManager::getDataSource('test_suite');
		$this->db = new DboMysqliTestDb($db->config);
		$this->model = new MysqliTestModel();
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
 * undocumented function
 *
 * @return void
 * @access public
 */
	function testTransactions() {
		$this->db->begin($this->model);
		$this->assertTrue($this->db->_transactionStarted);

		$beginSqlCalls = Set::extract('/.[query=START TRANSACTION]', $this->db->_queriesLog);
		$this->assertEqual(1, count($beginSqlCalls));

		$this->db->commit($this->model);
		$this->assertFalse($this->db->_transactionStarted);
	}
}
?>