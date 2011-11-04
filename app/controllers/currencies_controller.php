<?php
class CurrenciesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Currencies';

	function index() {
		$this->set('currencies', $this->Currency->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->check_unique()) {
				if ($this->Currency->save($this->data)) {
					$this->Session->setFlash('Currency info has been saved.');
					Cache::delete('currencies');	//clear cache
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate currency in the table');
			}
		}
	}
	
	function edit($id = null) {
		if (empty($this->data)) {
			$this->data = $this->Currency->read();
		} else {
			if ($this->check_unique($id)) {
				if ($this->Currency->save($this->data)) {
					$this->Session->setFlash('Currency info has been updated.');
					Cache::delete('currencies');	//clear cache
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate currency in the table');
			}
		}
	}
	
	//Check to see that the data entered is unique for this model
	function check_unique($id = null) {		
		$currency_code = $this->data['Currency']['currency_iso_code'];
		$currency_name = $this->data['Currency']['currency_name'];
		
		$conditions=array(
			'OR' => array(	
						'Currency.currency_iso_code =' => $currency_code,
						'Currency.currency_name =' => $currency_name
					),
			'Currency.id <>' => $id,
		);
	
		$params=array(
			'conditions' => $conditions, 
		);
		
		$num = $this->Currency->find('count', $params);
		if ($num > 0) { 
			return false;
		} 
		else {
			return true;
		}
	}
}
?>