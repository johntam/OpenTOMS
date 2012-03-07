<?php

class Ledger extends AppModel {
    var $name = 'Ledger';
	var $belongsTo ='Account, Trade, Fund, Currency, Sec';
	
	//Post trade journal entries to the general ledger
	function post($fund, $date) {	
		//get trades with trade date in the month
		$sqlparam=array(
			'conditions' => array(	'Trade.fund_id =' => $fund, 
									'Trade.act =' => 1, 
									'Trade.cancelled =' => 0, 
									'Trade.executed =' => 1
								 ), 
			'fields' => array('Trade.fund_id','Trade.trade_date','Trade.id','Trade.crd','Trade.trade_type_id','TradeType.trade_type','TradeType.debit_account_id',
							'TradeType.credit_account_id', 'Trade.consideration', 'Trade.notional_value','Currency.id','Currency.currency_iso_code','Trade.quantity',
							'Fund.fund_name', 'Sec.sec_name', 'Sec.id', 'Trade.price', 'Sec.valpoint'),
			'order' => array('Trade.trade_date ASC') 
		);
		
		//get the date of the previous balance calculation date
		App::import('model','Balance');
		$bal = new Balance();
		$prevdate = $bal->getPrevBalanceDate($fund, $date);
		
		if (empty($prevdate)) {
			$sqlparam['conditions']['Trade.trade_date <='] = $date;	//get all trades before $date since no prior balance date exists
		}
		else {
			$sqlparam['conditions']['AND'] = array('Trade.trade_date >' => $prevdate,
												   'Trade.trade_date <=' => $date);
		}
			
		$posts = $this->Trade->find('all', $sqlparam);
		
		
		echo debug($posts);
			
		//make inactive all previous ledger entries for this month that are unlocked (the check for the lock occurs in the controller).
		if ($this->updateAll( array('Ledger.act' => 0), array('Ledger.ledger_date =' => $date, 'Ledger.fund_id =' => $fund, 'Ledger.act =' => 1))) {
			foreach ($posts as $post) {
				$fund = $post['Trade']['fund_id'];
				$td = $post['Trade']['trade_date'];
				$tid = $post['Trade']['id'];
				$ttid = $post['Trade']['trade_type_id'];
				$ttcrd = $post['Trade']['crd'];
				$cons = abs($post['Trade']['consideration']);
				$cfd = abs($post['Trade']['notional_value']);
				if ($cfd) { $cfd = 1; } else { $cfd = 0; }
				$debitid = $post['TradeType']['debit_account_id'];
				$creditid = $post['TradeType']['credit_account_id'];
				$ccy = $post['Currency']['id'];
				$qty = $post['Trade']['quantity'];
				$secid = $post['Sec']['id'];
				$price = $post['Trade']['price'];
				$valp = $post['Sec']['valpoint'];
				$trinv = strtotime($ttcrd).':'.$qty.':'.$price.':'.$valp.';';
				
				//first line of double-entry
				if ($debitid > 1) {		//cash
					$secid2 = $this->Currency->getsecid($ccy);
					$qty2 = abs($cons);
				}
				else {
					$secid2 = $secid;
					$qty2 = $qty;
				}
				$data = array(	'act' => 1,
								'crd' => DboSource::expression('NOW()'),
								'fund_id' => $fund,
								'account_id' => $debitid,
								'ledger_date' => $date,
								'trade_date' => $td,
								'trade_id' => $tid,
								'trade_crd' => $ttcrd,
								'ledger_debit' => $cons,
								'ledger_credit' => 0,
								'ledger_cfd' => $cfd,
								'currency_id' => $ccy,
								'ledger_quantity' => $qty2,
								'sec_id' => $secid2,
								'trinv' => $trinv);		
				$this->create($data);
				$this->save();
				
				//second line of double-entry
				if ($creditid > 1) { 	//cash
					$data['sec_id']  = $this->Currency->getsecid($ccy);
					$data['ledger_quantity'] = -abs($cons);
				}
				else {
					$data['sec_id']  = $secid;
					$data['ledger_quantity'] = $qty;
				}
				$data['crd'] = DboSource::expression('NOW()');
				$data['account_id'] = $creditid;
				$data['ledger_debit'] = 0;
				$data['ledger_credit'] = $cons;
				
				$this->create($data);
				$this->save();
			}
			
			return($posts);
		}
		else {
			return null;
		}
	}
	
	
	//wipe all ledgers for a particular fund
	function wipe($fund) {
		$result = $this->updateAll( array('Ledger.act'=> 0), 
										array(	'Ledger.fund_id =' => $fund));
		return ($result);
	}
	
	
	//get date of last ledger posting date, PHP value of 0=false, anything else=true
	function getPrevPostDate($fund) {
		$fetch = $this->find('first', array('conditions'=>array('Ledger.fund_id ='=>$fund, 'Ledger.act ='=>1), 'order'=>'Ledger.ledger_date DESC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Ledger']['ledger_date'];
		}
	}
}

?>