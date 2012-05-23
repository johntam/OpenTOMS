<?php
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
		
		//administrators' group id = 4
		if ($userdata['group_id'] > 4) {
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
