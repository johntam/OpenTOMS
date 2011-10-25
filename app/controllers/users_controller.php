<?php
class UsersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Users';

	function login() {
		if ($this->Session->read('Auth.User')) {
			$this->Session->setFlash('You are logged in!');
			$this->redirect('/', null, false);
		}
	}  
 
	function logout() {
		$this->Session->setFlash('Good-Bye');
		$this->redirect($this->Auth->logout());
	}

	function beforeFilter() {
		parent::beforeFilter(); 
		//$this->Auth->allow(array('*'));
		$this->Auth->allowedActions = array('index');
	}

	function index() {
		$this->set('users', $this->User->find('all'));
	}
	
	function add() {
		$this->set('groups', $this->User->Group->find('list', array('fields'=>array('Group.name'))));
	
		if (!empty($this->data)) {
			if ($this->User->saveAll($this->data)) {
				$this->Session->setFlash('Your post has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
    
	
	function initDB() {
		$group =& $this->User->Group;
		//Allow admins to everything
		$group->id = 4;     
		$this->Acl->allow($group, 'controllers');
	 
		//allow managers to posts and widgets
		$group->id = 5;
		$this->Acl->deny($group, 'controllers');
		$this->Acl->allow($group, 'controllers/Trades');
		$this->Acl->allow($group, 'controllers/Funds');
	 
		//allow users to only add and edit on posts and widgets
		//$group->id = 3;
		//$this->Acl->deny($group, 'controllers');        
		//$this->Acl->allow($group, 'controllers/Posts/add');
		//$this->Acl->allow($group, 'controllers/Posts/edit');        
		//$this->Acl->allow($group, 'controllers/Widgets/add');
		//$this->Acl->allow($group, 'controllers/Widgets/edit');
		//we add an exit to avoid an ugly "missing views" error message
		echo "all done";
		exit;
	}

}
?>