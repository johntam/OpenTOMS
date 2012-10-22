<?php
class AccountsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Accounts';

	function index() {
		$this->set('accounts', $this->Account->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash('Accounts info has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		if (empty($this->data)) {
			$this->data = $this->Account->read();
		} 
		else {	
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash('Accounts info has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
}
?>