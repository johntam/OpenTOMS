<?php
class BrokersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Brokers';

	function index() {	
		$this->set('brokers', $this->Broker->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Broker->save($this->data)) {
				$this->Session->setFlash('Broker has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		
		if (empty($this->data)) {
			$this->data = $this->Broker->read();
		} else {
			if ($this->Broker->save($this->data)) {
				$this->Session->setFlash('Broker has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>