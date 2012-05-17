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

		//App::import('Model','ValuationReport');
		//echo debug($this->Session->read("Auth.User"));
		//echo debug($this->viewVars);
		
		
		
		$userdata = $this->Session->read("Auth.User");
		if (isset($this->viewVars['funds'])) {
			$funds = $this->viewVars['funds'];
			
			//check permissions for the group the user is a member of
			App::import('model','GroupPermission');
			$gp = new GroupPermission();
			$allowed = $gp->getAllowedFunds($userdata['group_id']);
			echo debug($allowed);
			
			foreach ($funds as $key=>$fund) {
				echo debug(array('key='=>$key, 'fund='=>$fund, 'in_array='=>!in_array($key, $allowed)));
				if (!in_array($key, $allowed)) {
					unset($funds[$key]);
				}
			}
			
		}
		
		echo debug($this->viewVars['funds']);
	}
}
?>
