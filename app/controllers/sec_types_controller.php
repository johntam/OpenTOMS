<?php
class SecTypesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'SecTypes';

	function index() {
		$this->set('sectypes', $this->SecType->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->SecType->save($this->data)) {
				$this->Session->setFlash('Security type has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function activate($id) {
		$this->SecType->status($id, 1);
		$this->redirect(array('action' => 'index'));
	}
	
	
	function deactivate($id) {
		$this->SecType->status($id, 0);
		$this->redirect(array('action' => 'index'));
	}
}
?>