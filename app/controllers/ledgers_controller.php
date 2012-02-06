<?php
class LedgersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Ledgers';

	function index() {
		//Get list of fund names
		$this->set('funds', $this->Ledger->Trade->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
	
	
	//Post trade journal entries to the general ledger
	function post() {
		echo debug($this->data);
		echo debug($this->params);
		
		$month = $this->data['Ledger']['accounting_period']['month'];
		$year = $this->data['Ledger']['accounting_period']['year'];
		$fund = $this->data['Ledger']['fund_id'];
		
		//get trades with trade date in the month
		$params=array(
			'conditions' => array('MONTH(Trade.trade_date) =' => $month, 'YEAR(Trade.trade_date) =' => $year, 'Trade.fund_id =' => $fund), 
			'fields' => array('Trade.trade_date','Trade.id','Trade.trade_type_id','TradeType.debit_account_id'),
			'order' => array('Trade.trade_date ASC') 
		);
		
		$this->set('posts', $this->Ledger->Trade->find('all', $params));
		
		//Get list of fund names
		$this->set('funds', $this->Ledger->Trade->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		//Just view the trades that would be posted
		if ($this->params['form']['Submit'] == 'View') {
			$this->render('index');
		}
		else {	//post these trades
			
		
			$this->Session->setFlash('Trades posted to journal.');
			$this->render('index');
		}
	}
	
	/*
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
	
	*/
}
?>