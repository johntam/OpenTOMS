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
			'fields' => array('Trade.trade_date','Trade.id','Trade.trade_type_id','TradeType.debit_account_id','TradeType.credit_account_id', 'Trade.consideration', 'Currency.currency_iso_code'),
			'order' => array('Trade.trade_date ASC') 
		);
		
		$posts = $this->Ledger->Trade->find('all', $params);
		$this->set('posts', $posts);
		
		//Get list of fund names
		$this->set('funds', $this->Ledger->Trade->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		//Just view the trades that would be posted
		if ($this->params['form']['Submit'] == 'View') {
			$this->render('index');
		}
		else {	//post these trades into the model database
			
			foreach ($posts as $post) {
				$td = $post['Trade']['trade_date'];
				$tid = $post['Trade']['id'];
				$ttid = $post['Trade']['trade_type_id'];
				$cons = $post['Trade']['consideration'];
				$debitid = $post['TradeType']['debit_account_id'];
				$creditid = $post['TradeType']['credit_account_id'];
				$ccy = $post['Currency']['currency_iso_code'];
			
				
			
			
			
			}
		
			$this->Session->setFlash('Trades posted to journal.');
			$this->render('index');
		}
	}
	

}
?>