<?php
class FundsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Funds';

	function index() {
		$this->set('funds', $this->Fund->find('all'));
	}
	
	function add() {
		$this->set('currencies', $this->Fund->Currency->find('list', 
										array('fields'=>array(
																'Currency.currency_iso_code'),
																'order'=>array('Currency.currency_iso_code'))));
		if (!empty($this->data)) {
			if ($this->Fund->save($this->data)) {
				$this->Session->setFlash('Fund has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		$this->set('currencies', $this->Fund->Currency->find('list', 
										array('fields'=>array(
																'Currency.currency_iso_code'),
																'order'=>array('Currency.currency_iso_code'))));
		if (empty($this->data)) {
			$this->data = $this->Fund->read();
		} else {
			if ($this->Fund->save($this->data)) {
				$this->Session->setFlash('Fund has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>