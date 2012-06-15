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
		$this->Session->setFlash('You have logged out');
		//$this->redirect($this->Auth->logout());
		$this->Auth->logout();
		$this->redirect(array('action' => 'login'));
	}

	function beforeFilter() {
		parent::beforeFilter(); 
		//$this->Auth->allow(array('*'));
		$this->Auth->allowedActions = array('initDB','welcome','logout');
	}

	function index() {
		$this->set('users', $this->User->find('all'));
	}
	
	function add() {
		$this->set('groups', $this->User->Group->find('list', array('fields'=>array('Group.name'))));
	
		if (!empty($this->data)) {
			if ($this->User->saveAll($this->data)) {
				$this->Session->setFlash('User has been added.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
    
	function welcome() {}
	
	function initDB() {
		$group =& $this->User->Group;
		//Allow admins to everything
		$group->id = 4;     
		$this->Acl->allow($group, 'controllers');
	 
		//allow clients to enter new trades only
		$group->id = 5;
		$this->Acl->deny($group, 'controllers');
		$this->Acl->allow($group, 'controllers/Trades/add');
		
		//no access to admin pages for RGL
		$group->id = 7;
		$this->Acl->allow($group, 'controllers');
		$this->Acl->deny($group, 'controllers/Users');
		$this->Acl->deny($group, 'controllers/Groups');
		$this->Acl->deny($group, 'controllers/Prices');
		
		//no access to admin pages for Kestrel
		$group->id = 6;
		$this->Acl->allow($group, 'controllers');
		$this->Acl->deny($group, 'controllers/Users');
		$this->Acl->deny($group, 'controllers/Groups');
		$this->Acl->deny($group, 'controllers/Prices');
		$this->Acl->deny($group, 'controllers/Traders');
		
		//no access to admin pages for Guest accounts
		$group->id = 8;
		$this->Acl->allow($group, 'controllers');
		$this->Acl->deny($group, 'controllers/Users');
		$this->Acl->deny($group, 'controllers/Groups');
		$this->Acl->deny($group, 'controllers/Prices');
		$this->Acl->deny($group, 'controllers/Traders');
		$this->Acl->deny($group, 'controllers/Funds');
		$this->Acl->deny($group, 'controllers/Secs');
		$this->Acl->deny($group, 'controllers/SecTypes');
		$this->Acl->deny($group, 'controllers/Reasons');
		$this->Acl->deny($group, 'controllers/Brokers');
		$this->Acl->deny($group, 'controllers/Custodians');
		$this->Acl->deny($group, 'controllers/Countries');
		$this->Acl->deny($group, 'controllers/Exchanges');
		$this->Acl->deny($group, 'controllers/Industries');
		$this->Acl->deny($group, 'controllers/Currencies');
		$this->Acl->deny($group, 'controllers/Holidays');
		$this->Acl->deny($group, 'controllers/Settlements');
		$this->Acl->deny($group, 'controllers/Accounts');
		
	 
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
