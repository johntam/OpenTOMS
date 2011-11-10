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
}
?>
