<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.tests.fixtures
 * @since         CakePHP(tm) v 1.2.0.4667
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package       cake
 * @subpackage    cake.tests.fixtures
 */
class AccountFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'Aco'
 * @access public
 */
	var $name = 'Account';
	var $table = 'Accounts';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'iAccountId'		=> array('type' => 'integer', 'key' => 'primary'),
		'cDescription'	=> array('type' => 'string', 'length' => 10, 'null' => true)
	);
/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array(
		array('cDescription' => 'gwoo'),
		array('cDescription' => 'phpnut'),
		array('cDescription' => 'schreck'),
		array('cDescription' => 'dude')
	);
}

?>