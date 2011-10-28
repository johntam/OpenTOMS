<?php
class TradersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Traders';

	function index() {
		$this->set('traders', $this->Trader->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Trader->save($this->data)) {
				$this->Session->setFlash('Trader has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>