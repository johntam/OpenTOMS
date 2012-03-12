<?php
class CustodiansController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Custodians';

	function index() {	
		$this->set('custodians', $this->Custodian->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Custodian->save($this->data)) {
				$this->Session->setFlash('Custodian has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		if (empty($this->data)) {
			$this->data = $this->Custodian->read();
		} else {
			if ($this->Custodian->save($this->data)) {
				$this->Session->setFlash('Custodian has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>