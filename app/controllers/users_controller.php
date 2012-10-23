<?php
/*
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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

		//create initial groups
		//Allow admins to everything
		$data['Group'] = array('name' => 'Administrators');
		$group->create($data);
		$group->save();
		$admin_group_id = $group->id;
		$this->Acl->allow($group, 'controllers');
	 
		//allow traders to enter new trades only
		$data['Group'] = array('name' => 'Traders');
		$group->create($data);
		$group->save();
		$this->Acl->deny($group, 'controllers');
		$this->Acl->allow($group, 'controllers/Trades/add');
		
		//no access to admin pages for Guest accounts
		$data['Group'] = array('name' => 'Guests');
		$group->create($data);
		$group->save();
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
		echo "aros and aros_acos tables populated</br>";

		//create default Admin user
		$data['User'] = array('username' => 'admin', 'password' => $this->Auth->password(''), 'group_id' => $admin_group_id);
		$this->User->save($data);
		echo "user admin created with blank password</br>";		

		exit;
	}

}
