<?php
class ExchangesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Exchanges';

	function index() {
		$this->set('exchanges', $this->Exchange->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->check_unique()) {
				if ($this->Exchange->save($this->data)) {
					$this->Session->setFlash('Exchange info has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate exchange in the table');
			}
		}
	}
	
	function edit($id = null) {
		if (empty($this->data)) {
			$this->data = $this->Exchange->read();
		} else {
			if ($this->check_unique($id)) {
				if ($this->Exchange->save($this->data)) {
					$this->Session->setFlash('Exchange info has been updated.');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate exchange in the table');
			}
		}
	}
	
	//Check to see that the data entered is unique for this model
	function check_unique($id = null) {		
		$exchange_code = $this->data['Exchange']['exchange_code'];
		$exchange_name = $this->data['Exchange']['exchange_name'];
		
		$conditions=array(
			'OR' => array(	
						'Exchange.exchange_code =' => $exchange_code,
						'Exchange.exchange_name =' => $exchange_name
					),
			'Exchange.id <>' => $id,
		);
	
		$params=array(
			'conditions' => $conditions, 
		);
		
		$num = $this->Exchange->find('count', $params);
		if ($num > 0) { 
			return false;
		} 
		else {
			return true;
		}
	}
}
?>