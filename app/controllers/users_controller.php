<?php
/**
 * Users Controller
 * Dummy users controller for example code
 * PHP 5
 *
 * @category Controller
 * @package  EP
 * @version  1.0
 * @author   Emmanuel P <hello@pozo.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     github
 */ 
class UsersController extends AppController
{
   
	var $name = "users";
	var $layout = "ajax"; //Change this to match your layouts
	
	function beforeFilter(){
		$this->validateLoginStatus();
	}
	
	function index(){
		$this->set('user_data',$this->Session->read('User'));	
	}
	
	function login(){
		
	}
	
	function logout(){
		$this->Session->destroy('User');
		$this->redirect('/');
		exit();
	}
	
	//Used to detect a user session. 
	private function validateLoginStatus(){
		if($this->action != 'login')
		{ 
			if($this->Session->check('User') == false)
			{
				$this->redirect(array('action'=>'login'));
				exit();
			}
		}
	}
}
?>