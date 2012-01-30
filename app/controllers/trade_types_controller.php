<?php
class TradeTypesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'TradeTypes';

	function index() {		
		$this->set('tradetypes', $this->TradeType->find('all', array('fields' => array('TradeType.id','TradeType.trade_type','TradeType.category','Debit.account_name','Credit.account_name'))));
	}
	
	function add() {
		$this->set('accountlist', $this->TradeType->Debit->find('list', array('fields'=>array('Debit.account_name'), 'order'=>'Debit.account_name')));
		
		if (!empty($this->data)) {
			if ($this->TradeType->save($this->data)) {
				$this->Session->setFlash('Trade type has been saved.');
				Cache::delete('creditdebit');	//clear cache used in trades page
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		$this->set('accountlist', $this->TradeType->Debit->find('list', array('fields'=>array('Debit.account_name'), 'order'=>'Debit.account_name')));
		
		if (empty($this->data)) {
			$this->data = $this->TradeType->read();
		} 
		else {	
			if ($this->TradeType->save($this->data)) {
				$this->Session->setFlash('Trade Type info has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>