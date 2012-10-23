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

class AppController extends Controller {
    var $components = array('Acl', 'Auth', 'Session');
    var $helpers = array('Html', 'Form', 'Session','Cycle');

    function beforeFilter() {	
        //Configure AuthComponent
	//$this->Auth->actionPath = 'controllers/';
        $this->Auth->authorize = 'actions';
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'logout');
        //$this->Auth->loginRedirect = array('controller' => 'trades', 'action' => 'add');
	$this->Auth->allowedActions = array('display');
		
    }
	
	function beforeRender() {
		parent::beforeRender();
		
		$userdata = $this->Session->read("Auth.User");
		if (empty($userdata)) {
			//hide menu items
			$this->set('hide_pricing','y');
			$this->set('hide_tradetypes','y');
			$this->set('hide_admin','y');
			$this->set('hide_worklist','y');
			$this->set('hide_traders','y');
			$this->set('show_login','y');
			return;
		}
		

		//ensure that the user doesn't see any fund data that he/she isn't supposed to see
		//this is controlled by the user's group being associated with fund id's in the
		//group_permissions table
		//The next line ensures that administrator logins have no restrictions (administrator
		//group id is usually = 1 
		if ($userdata['group_id'] > 1) {
			if (isset($this->viewVars['funds'])) {
				$funds = &$this->viewVars['funds'];
				
				//check permissions for the group the user is a member of
				App::import('model','GroupPermission');
				$gp = new GroupPermission();
				$allowed = $gp->getAllowedFunds($userdata['group_id']);
				
				foreach ($funds as $key=>$fund) {
					if (!in_array($key, $allowed)) {
						//make sure the user doesn't see funds he isn't supposed to see
						unset($funds[$key]);
					}
				}
			}
			
			//hide some menu items
			$this->set('hide_pricing','y');
			$this->set('hide_tradetypes','y');
			$this->set('hide_admin','y');
			$this->set('hide_worklist','y');
			$this->set('hide_traders','y');
		}
	}
}
?>
