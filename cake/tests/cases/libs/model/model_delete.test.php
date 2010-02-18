<?php
/* SVN FILE: $Id: model.test.php 8225 2009-07-08 03:25:30Z mark_story $ */
/**
 * ModelDeleteTest file
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
 * @subpackage    cake.tests.cases.libs.model
 * @since         CakePHP(tm) v 1.2.0.4206
 * @version       $Revision: 8225 $
 * @modifiedby    $LastChangedBy: mark_story $
 * @lastmodified  $Date: 2009-07-07 23:25:30 -0400 (Tue, 07 Jul 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
require_once dirname(__FILE__) . DS . 'model.test.php';
require_once dirname(__FILE__) . DS . 'model_delete.test.php';
/**
 * ModelDeleteTest
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.operations
 */
class ModelDeleteTest extends BaseModelTest {
/**
 * testDeleteHabtmReferenceWithConditions method
 *
 * @access public
 * @return void
 */
	function testDeleteHabtmReferenceWithConditions() {
		$this->loadFixtures('Portfolio', 'Item', 'ItemsPortfolio');

		$Portfolio =& new Portfolio();
		$Portfolio->hasAndBelongsToMany['Item']['conditions'] = array('ItemsPortfolio.item_id >' => 1);

		$result = $Portfolio->find('first', array(
			'conditions' => array('Portfolio.id' => 1)
		));
		$expected = array(
			array(
				'id' => 3,
				'syfile_id' => 3,
				'published' => 0,
				'name' => 'Item 3',
				'ItemsPortfolio' => array(
					'id' => 3,
					'item_id' => 3,
					'portfolio_id' => 1
			)),
			array(
				'id' => 4,
				'syfile_id' => 4,
				'published' => 0,
				'name' => 'Item 4',
				'ItemsPortfolio' => array(
					'id' => 4,
					'item_id' => 4,
					'portfolio_id' => 1
			)),
			array(
				'id' => 5,
				'syfile_id' => 5,
				'published' => 0,
				'name' => 'Item 5',
				'ItemsPortfolio' => array(
					'id' => 5,
					'item_id' => 5,
					'portfolio_id' => 1
		)));
		$this->assertEqual($result['Item'], $expected);

		$result = $Portfolio->ItemsPortfolio->find('all', array(
			'conditions' => array('ItemsPortfolio.portfolio_id' => 1)
		));
		$expected = array(
			array(
				'ItemsPortfolio' => array(
					'id' => 1,
					'item_id' => 1,
					'portfolio_id' => 1
			)),
			array(
				'ItemsPortfolio' => array(
					'id' => 3,
					'item_id' => 3,
					'portfolio_id' => 1
			)),
			array(
				'ItemsPortfolio' => array(
					'id' => 4,
					'item_id' => 4,
					'portfolio_id' => 1
			)),
			array(
				'ItemsPortfolio' => array(
					'id' => 5,
					'item_id' => 5,
					'portfolio_id' => 1
		)));
		$this->assertEqual($result, $expected);

		$Portfolio->delete(1);

		$result = $Portfolio->find('first', array(
			'conditions' => array('Portfolio.id' => 1)
		));
		$this->assertFalse($result);

		$result = $Portfolio->ItemsPortfolio->find('all', array(
			'conditions' => array('ItemsPortfolio.portfolio_id' => 1)
		));
		$this->assertFalse($result);
	}
/**
 * testDeleteArticleBLinks method
 *
 * @access public
 * @return void
 */
	function testDeleteArticleBLinks() {
		$this->loadFixtures('Article', 'ArticlesTag', 'Tag');
		$TestModel =& new ArticleB();

		$result = $TestModel->ArticlesTag->find('all');
		$expected = array(
			array('ArticlesTag' => array('article_id' => '1', 'tag_id' => '1')),
			array('ArticlesTag' => array('article_id' => '1', 'tag_id' => '2')),
			array('ArticlesTag' => array('article_id' => '2', 'tag_id' => '1')),
			array('ArticlesTag' => array('article_id' => '2', 'tag_id' => '3'))
			);
		$this->assertEqual($result, $expected);

		$TestModel->delete(1);
		$result = $TestModel->ArticlesTag->find('all');

		$expected = array(
			array('ArticlesTag' => array('article_id' => '2', 'tag_id' => '1')),
			array('ArticlesTag' => array('article_id' => '2', 'tag_id' => '3'))
		);
		$this->assertEqual($result, $expected);
	}
/**
 * testDeleteDependentWithConditions method
 *
 * @access public
 * @return void
 */
	function testDeleteDependentWithConditions() {
		$this->loadFixtures('Cd','Book','OverallFavorite');

		$Cd =& new Cd();
		$OverallFavorite =& new OverallFavorite();

		$Cd->del(1);

		$result = $OverallFavorite->find('all', array(
			'fields' => array('model_type', 'model_id', 'priority')
		));
		$expected = array(
			array(
				'OverallFavorite' => array(
					'model_type' => 'Book',
					'model_id' => 1,
					'priority' => 2
		)));

		$this->assertTrue(is_array($result));
		$this->assertEqual($result, $expected);
	}
/**
 * testDel method
 *
 * @access public
 * @return void
 */
	function testDel() {
		$this->loadFixtures('Article');
		$TestModel =& new Article();

		$result = $TestModel->del(2);
		$this->assertTrue($result);

		$result = $TestModel->read(null, 2);
		$this->assertFalse($result);

		$TestModel->recursive = -1;
		$result = $TestModel->find('all', array(
			'fields' => array('id', 'title')
		));
		$expected = array(
			array('Article' => array(
				'id' => 1,
				'title' => 'First Article'
			)),
			array('Article' => array(
				'id' => 3,
				'title' => 'Third Article'
		)));
		$this->assertEqual($result, $expected);

		$result = $TestModel->del(3);
		$this->assertTrue($result);

		$result = $TestModel->read(null, 3);
		$this->assertFalse($result);

		$TestModel->recursive = -1;
		$result = $TestModel->find('all', array(
			'fields' => array('id', 'title')
		));
		$expected = array(
			array('Article' => array(
				'id' => 1,
				'title' => 'First Article'
		)));

		$this->assertEqual($result, $expected);


		// make sure deleting a non-existent record doesn't break save()
		// ticket #6293
		$this->loadFixtures('Uuid');
		$Uuid =& new Uuid();
		$data = array(
			'B607DAB9-88A2-46CF-B57C-842CA9E3B3B3',
			'52C8865C-10EE-4302-AE6C-6E7D8E12E2C8',
			'8208C7FE-E89C-47C5-B378-DED6C271F9B8');
		foreach ($data as $id) {
			$Uuid->save(array('id' => $id));
		}
		$Uuid->del('52C8865C-10EE-4302-AE6C-6E7D8E12E2C8');
		$Uuid->del('52C8865C-10EE-4302-AE6C-6E7D8E12E2C8');
		foreach ($data as $id) {
			$Uuid->save(array('id' => $id));
		}
		$result = $Uuid->find('all', array(
			'conditions' => array('id' => $data),
			'fields' => array('id'),
			'order' => 'id'));
		$expected = array(
			array('Uuid' => array(
				'id' => '52C8865C-10EE-4302-AE6C-6E7D8E12E2C8')),
			array('Uuid' => array(
				'id' => '8208C7FE-E89C-47C5-B378-DED6C271F9B8')),
			array('Uuid' => array(
				'id' => 'B607DAB9-88A2-46CF-B57C-842CA9E3B3B3')));
		$this->assertEqual($result, $expected);
	}
/**
 * testDeleteAll method
 *
 * @access public
 * @return void
 */
	function testDeleteAll() {
		$this->loadFixtures('Article');
		$TestModel =& new Article();

		$data = array('Article' => array(
			'user_id' => 2,
			'id' => 4,
			'title' => 'Fourth Article',
			'published' => 'N'
		));
		$result = $TestModel->set($data) && $TestModel->save();
		$this->assertTrue($result);

		$data = array('Article' => array(
			'user_id' => 2,
			'id' => 5,
			'title' => 'Fifth Article',
			'published' => 'Y'
		));
		$result = $TestModel->set($data) && $TestModel->save();
		$this->assertTrue($result);

		$data = array('Article' => array(
			'user_id' => 1,
			'id' => 6,
			'title' => 'Sixth Article',
			'published' => 'N'
		));
		$result = $TestModel->set($data) && $TestModel->save();
		$this->assertTrue($result);

		$TestModel->recursive = -1;
		$result = $TestModel->find('all', array(
			'fields' => array('id', 'user_id', 'title', 'published')
		));

		$expected = array(
			array('Article' => array(
				'id' => 1,
				'user_id' => 1,
				'title' => 'First Article',
				'published' => 'Y'
			)),
			array('Article' => array(
				'id' => 2,
				'user_id' => 3,
				'title' => 'Second Article',
				'published' => 'Y'
			)),
			array('Article' => array(
				'id' => 3,
				'user_id' => 1,
				'title' => 'Third Article',
				'published' => 'Y')),
			array('Article' => array(
				'id' => 4,
				'user_id' => 2,
				'title' => 'Fourth Article',
				'published' => 'N'
			)),
			array('Article' => array(
				'id' => 5,
				'user_id' => 2,
				'title' => 'Fifth Article',
				'published' => 'Y'
			)),
			array('Article' => array(
				'id' => 6,
				'user_id' => 1,
				'title' => 'Sixth Article',
				'published' => 'N'
		)));

		$this->assertEqual($result, $expected);

		$result = $TestModel->deleteAll(array('Article.published' => 'N'));
		$this->assertTrue($result);

		$TestModel->recursive = -1;
		$result = $TestModel->find('all', array(
			'fields' => array('id', 'user_id', 'title', 'published')
		));
		$expected = array(
			array('Article' => array(
				'id' => 1,
				'user_id' => 1,
				'title' => 'First Article',
				'published' => 'Y'
			)),
			array('Article' => array(
				'id' => 2,
				'user_id' => 3,
				'title' => 'Second Article',
				'published' => 'Y'
			)),
			array('Article' => array(
				'id' => 3,
				'user_id' => 1,
				'title' => 'Third Article',
				'published' => 'Y'
			)),
			array('Article' => array(
				'id' => 5,
				'user_id' => 2,
				'title' => 'Fifth Article',
				'published' => 'Y'
		)));
		$this->assertEqual($result, $expected);

		$data = array('Article.user_id' => array(2, 3));
		$result = $TestModel->deleteAll($data, true, true);
		$this->assertTrue($result);

		$TestModel->recursive = -1;
		$result = $TestModel->find('all', array(
			'fields' => array('id', 'user_id', 'title', 'published')
		));
		$expected = array(
			array('Article' => array(
				'id' => 1,
				'user_id' => 1,
				'title' => 'First Article',
				'published' => 'Y'
			)),
			array('Article' => array(
				'id' => 3,
				'user_id' => 1,
				'title' => 'Third Article',
				'published' => 'Y'
		)));
		$this->assertEqual($result, $expected);

		$result = $TestModel->deleteAll(array('Article.user_id' => 999));
		$this->assertTrue($result, 'deleteAll returned false when all no records matched conditions. %s');
	}
/**
 * testRecursiveDel method
 *
 * @access public
 * @return void
 */
	function testRecursiveDel() {
		$this->loadFixtures('Article', 'Comment', 'Attachment');
		$TestModel =& new Article();

		$result = $TestModel->del(2);
		$this->assertTrue($result);

		$TestModel->recursive = 2;
		$result = $TestModel->read(null, 2);
		$this->assertFalse($result);

		$result = $TestModel->Comment->read(null, 5);
		$this->assertFalse($result);

		$result = $TestModel->Comment->read(null, 6);
		$this->assertFalse($result);

		$result = $TestModel->Comment->Attachment->read(null, 1);
		$this->assertFalse($result);

		$result = $TestModel->find('count');
		$this->assertEqual($result, 2);

		$result = $TestModel->Comment->find('count');
		$this->assertEqual($result, 4);

		$result = $TestModel->Comment->Attachment->find('count');
		$this->assertEqual($result, 0);
	}
/**
 * testDependentExclusiveDelete method
 *
 * @access public
 * @return void
 */
	function testDependentExclusiveDelete() {
		$this->loadFixtures('Article', 'Comment');
		$TestModel =& new Article10();

		$result = $TestModel->find('all');
		$this->assertEqual(count($result[0]['Comment']), 4);
		$this->assertEqual(count($result[1]['Comment']), 2);
		$this->assertEqual($TestModel->Comment->find('count'), 6);

		$TestModel->delete(1);
		$this->assertEqual($TestModel->Comment->find('count'), 2);
	}
/**
 * testDeleteLinks method
 *
 * @access public
 * @return void
 */
	function testDeleteLinks() {
		$this->loadFixtures('Article', 'ArticlesTag', 'Tag');
		$TestModel =& new Article();

		$result = $TestModel->ArticlesTag->find('all');
		$expected = array(
			array('ArticlesTag' => array(
				'article_id' => '1',
				'tag_id' => '1'
			)),
			array('ArticlesTag' => array(
				'article_id' => '1',
				'tag_id' => '2'
			)),
			array('ArticlesTag' => array(
				'article_id' => '2',
				'tag_id' => '1'
			)),
			array('ArticlesTag' => array(
				'article_id' => '2',
				'tag_id' => '3'
		)));
		$this->assertEqual($result, $expected);

		$TestModel->delete(1);
		$result = $TestModel->ArticlesTag->find('all');

		$expected = array(
			array('ArticlesTag' => array(
				'article_id' => '2',
				'tag_id' => '1'
			)),
			array('ArticlesTag' => array(
				'article_id' => '2',
				'tag_id' => '3'
		)));
		$this->assertEqual($result, $expected);

		$result = $TestModel->deleteAll(array('Article.user_id' => 999));
		$this->assertTrue($result, 'deleteAll returned false when all no records matched conditions. %s');
	}
/**
 * testHabtmDeleteLinksWhenNoPrimaryKeyInJoinTable method
 *
 * @access public
 * @return void
 */
	function testHabtmDeleteLinksWhenNoPrimaryKeyInJoinTable() {

		$this->loadFixtures('Apple', 'Device', 'ThePaperMonkies');
		$ThePaper =& new ThePaper();
		$ThePaper->id = 1;
		$ThePaper->save(array('Monkey' => array(2, 3)));

		$result = $ThePaper->findById(1);
		$expected = array(
			array(
				'id' => '2',
				'device_type_id' => '1',
				'name' => 'Device 2',
				'typ' => '1'
			),
			array(
				'id' => '3',
				'device_type_id' => '1',
				'name' => 'Device 3',
				'typ' => '2'
		));
		$this->assertEqual($result['Monkey'], $expected);

		$ThePaper =& new ThePaper();
		$ThePaper->id = 2;
		$ThePaper->save(array('Monkey' => array(2, 3)));

		$result = $ThePaper->findById(2);
		$expected = array(
			array(
				'id' => '2',
				'device_type_id' => '1',
				'name' => 'Device 2',
				'typ' => '1'
			),
			array(
				'id' => '3',
				'device_type_id' => '1',
				'name' => 'Device 3',
				'typ' => '2'
		));
		$this->assertEqual($result['Monkey'], $expected);

		$ThePaper->delete(1);
		$result = $ThePaper->findById(2);
		$expected = array(
			array(
				'id' => '2',
				'device_type_id' => '1',
				'name' => 'Device 2',
				'typ' => '1'
			),
			array(
				'id' => '3',
				'device_type_id' => '1',
				'name' => 'Device 3',
				'typ' => '2'
		));
		$this->assertEqual($result['Monkey'], $expected);
	}

}

?>