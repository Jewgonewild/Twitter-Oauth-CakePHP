<?php
/* SVN FILE: $Id$ */
/**
 * XmlGroupTest file
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
 * @subpackage    cake.tests.groups
 * @since         CakePHP(tm) v 1.2.0.4206
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * XmlGroupTest class
 *
 * This test group will run view class tests (view, theme).
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class XmlGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string 'All core views'
 * @access public
 */
	var $label = 'Xml based classes (Xml, XmlHelper and RssHelper)';
/**
 * XmlGroupTest method
 *
 * @access public
 * @return void
 */
	function XmlGroupTest() {
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'xml');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'view' . DS . 'helpers' . DS .'rss');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'view' . DS . 'helpers' . DS .'xml');
	}
}
?>