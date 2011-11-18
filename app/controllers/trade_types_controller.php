<?php
class TradeTypesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'TradeTypes';

	function index() {
		$this->set('tradetypes', $this->TradeType->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->TradeType->save($this->data)) {
				$this->Session->setFlash('Trade type has been saved.');
				Cache::delete('creditdebit');	//clear cache used in trades page
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>