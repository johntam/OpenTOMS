<?php

class Ledger extends AppModel {
    var $name = 'Ledger';
	var $belongsTo ='Account, Trade, Fund, Currency, Sec';
	
	//Post trade journal entries to the general ledger
	function post($month, $year, $fund, $accountid) {
		//get trades with trade date in the month
		if ($accountid == 0) { $accountid = '%'; }
		$sqlparam=array(
			'conditions' => array(	'MONTH(Trade.trade_date) =' => $month, 
									'YEAR(Trade.trade_date) =' => $year, 
									'Trade.fund_id =' => $fund, 
									'Trade.act' => 1, 
									'Trade.cancelled' => 0, 
									'Trade.executed' => 1,
									'OR' => array( 'TradeType.debit_account_id LIKE' => $accountid, 'TradeType.credit_account_id LIKE' => $accountid )), 
			'fields' => array('Trade.fund_id','Trade.trade_date','Trade.id','Trade.trade_type_id','TradeType.trade_type','TradeType.debit_account_id',
							'TradeType.credit_account_id', 'Trade.consideration', 'Currency.id','Currency.currency_iso_code','Trade.quantity','Fund.fund_name', 'Sec.sec_name', 'Sec.id'),
			'order' => array('Trade.trade_date ASC') 
		);
		
		$posts = $this->Trade->find('all', $sqlparam);
			
		//make inactive all previous ledger entries for this month (that are unlocked)
		if ($this->updateAll( array('Ledger.act' => 0), array('Ledger.ledger_month =' => $month, 'Ledger.ledger_year =' => $year, 'Ledger.fund_id =' => $fund, 'Ledger.account_id LIKE' => $accountid, 'Ledger.act =' => 1))) {
			foreach ($posts as $post) {
				$fund = $post['Trade']['fund_id'];
				$td = $post['Trade']['trade_date'];
				$tid = $post['Trade']['id'];
				$ttid = $post['Trade']['trade_type_id'];
				$cons = abs($post['Trade']['consideration']);
				$debitid = $post['TradeType']['debit_account_id'];
				$creditid = $post['TradeType']['credit_account_id'];
				$ccy = $post['Currency']['id'];
				$qty = $post['Trade']['quantity'];
				$secid = $post['Sec']['id'];
				
				//first line of double-entry
				if ($debitid > 1) {$secid = $this->Currency->getsecid($ccy);}	//if this is cash type account, put the ccy in place of security
				$data = array(	'act' => 1,
								'locked' => 0,
								'crd' => DboSource::expression('NOW()'),
								'fund_id' => $fund,
								'account_id' => $debitid,
								'ledger_month' => $month,
								'ledger_year' => $year,
								'ledger_date' => $td,
								'trade_id' => $tid,
								'ledger_debit' => $cons,
								'ledger_credit' => 0,
								'ledger_balance' => 0,
								'currency_id' => $ccy,
								'ledger_quantity' => $qty,
								'sec_id' => $secid );		
				$this->create($data);
				$this->save();
				
				//second line of double-entry
				if ($creditid > 1) {$secid = $this->Currency->getsecid($ccy);}	//if this is cash type account, put the ccy in place of security
				$data['crd'] = DboSource::expression('NOW()');
				$data['account_id'] = $creditid;
				$data['ledger_debit'] = 0;
				$data['ledger_credit'] = $cons;
				$data['sec_id'] = $secid;
				$this->create($data);
				$this->save();
			}
			
			return($posts);
		}
		else {
			return false;
		}
	}
	
	//lock month end (called from Fix Balances screen)
	function lock($fund, $month, $year) {
		$result = $this->updateAll( array('Ledger.locked' => 1), 
										array(	'Ledger.ledger_month =' => $month, 
												'Ledger.ledger_year =' => $year, 
												'Ledger.fund_id =' => $fund,
												'Ledger.act =' => 1));
		return ($result);
	}
	
	//unlock month end (called from Fix Balances screen)
	function unlock($fund, $month, $year) {
		$result = $this->updateAll( array('Ledger.locked' => 0), 
										array(	'Ledger.ledger_month =' => $month, 
												'Ledger.ledger_year =' => $year, 
												'Ledger.fund_id =' => $fund,
												'Ledger.act =' => 1));
		return ($result);
	}
}

?>