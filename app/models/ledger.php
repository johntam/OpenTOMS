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
							'Fund.fund_name', 'Sec.sec_name', 'Sec.id', 'Trade.execution_price', 'Sec.valpoint','Trade.commission','Trade.tax','Trade.other_costs', 
							'Sec.currency_id', 'Debit.account_type', 'Credit.account_type', 'Trade.settlement_date'),
			'joins' => array(	array('table'=>'trade_types',
									  'alias'=>'TradeType2',
									  'type'=>'inner',
									  'foreignKey'=>false,
									  'conditions'=>
											array(	'TradeType2.id=Trade.trade_type_id')
									  ),
								array('table'=>'accounts',
									  'alias'=>'Debit',
									  'type'=>'inner',
									  'foreignKey'=>false,
									  'conditions'=>
											array(	'Debit.id=TradeType2.debit_account_id')
									  ),
								array('table'=>'accounts',
									  'alias'=>'Credit',
									  'type'=>'inner',
									  'foreignKey'=>false,
									  'conditions'=>
											array(	'Credit.id=TradeType2.credit_account_id')
									  )
								),
			'order' => array('Trade.trade_date ASC', 'Trade.crd ASC')
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
		
		//get trades from the last balance calculation date to the date selected
		$posts = $this->Trade->find('all', $sqlparam);
		
		if (empty($posts)) {
			//no trades to post, however must create a dummy line in the table so that we can choose this date on the balances screen
			$data = array(	'act' => 1,
							'crd' => DboSource::expression('NOW()'),
							'fund_id' => $fund,
							'account_id' => 1,	//cash
							'ledger_date' => $date,
							'trade_date' => $date,
							'settlement_date' => $date,
							'trade_id' => 0,
							'trade_crd' => DboSource::expression('NOW()'),
							'ledger_debit' => 0,
							'ledger_credit' => 0,
							'ledger_cfd' => 0,
							'currency_id' => 1,	//USD
							'ledger_quantity' => 0,
							'sec_id' => -1);
				$this->create($data);
				$this->save();
				return null;
		}
		
		$trad_cost_acc_id = $this->Account->getNamed('Trading Costs');
		
		//make inactive all previous ledger entries for this month that are unlocked (the check for the lock occurs in the controller).
		if ($this->updateAll( array('Ledger.act' => 0), array('Ledger.ledger_date =' => $date, 'Ledger.fund_id =' => $fund, 'Ledger.act =' => 1))) {
			$last_td = 0;
			$last_td_crd = 0;
			foreach ($posts as $post) {
				$fund = $post['Trade']['fund_id'];
				$debitacctype = $post['Debit']['account_type'];
				$creditacctype = $post['Credit']['account_type'];
				$td = $post['Trade']['trade_date'];
				$sd = $post['Trade']['settlement_date'];
				$tid = $post['Trade']['id'];
				$ttid = $post['Trade']['trade_type_id'];
				$tcrd = $post['Trade']['crd'];
				$tccy = $post['Currency']['id'];
				$trading_costs = $post['Trade']['commission'] + $post['Trade']['tax'] + $post['Trade']['other_costs'];	//always positive
				$consX = $post['Trade']['consideration'] + $trading_costs;
				$cons = $post['Trade']['consideration'];
				$cfd = abs($post['Trade']['notional_value']);
				if ($cfd) { $cfd = 1; } else { $cfd = 0; }
				$debitid = $post['TradeType']['debit_account_id'];
				$creditid = $post['TradeType']['credit_account_id'];
				$ccy = $post['Sec']['currency_id'];
				$qty = $post['Trade']['quantity'];
				$secid = $post['Sec']['id'];
				$price = $post['Trade']['execution_price'];
				$valp = $post['Sec']['valpoint'];
				//Get the order right for the trinv so that FIFO etc work properly
				if (strtotime($td) == $last_td) {
					$trinv = ($last_td_crd+1).':'.preg_replace('/(\.)(\d*?)(0+)$/', '${1}${2}0',$qty).':'.preg_replace('/(\.)(\d*?)(0+)$/', '${1}${2}0',$price).':'.preg_replace('/(\.)(\d*?)(0+)$/', '${1}${2}0',$valp).';';
					$last_td_crd += 1;
				}
				else {
					$trinv = strtotime($td).':'.preg_replace('/(\.)(\d*?)(0+)$/', '${1}${2}0',$qty).':'.preg_replace('/(\.)(\d*?)(0+)$/', '${1}${2}0',$price).':'.preg_replace('/(\.)(\d*?)(0+)$/', '${1}${2}0',$valp).';';
					$last_td_crd = strtotime($td);
				}
				$last_td = strtotime($td);
				//
				
				//first line of double-entry, doing the DEBIT side
				if ($debitid > 1) {		//cash type account
					$secid2 = $this->Currency->getsecid($tccy);
					$qty2 = $cons;
					$tr2 = '';
					$ccy2 = $tccy;
					$cfd2 = 0;
					//if (($debitid == 2) && ($debitacctype == 'Assets') || ($debitacctype == 'Expenses'))  {
						$cons_debit = abs($cons);
						$cons_credit = 0;
					//}
					//else {
					//	$cons_debit = 0;
					//	$cons_credit = abs($cons);
					//}
				}
				else {	//special case of stocks
					$secid2 = $secid;
					$qty2 = $qty;
					$tr2 = $trinv;
					$ccy2 = $ccy;
					$cfd2 = $cfd;
					////
					$cons_debit = abs($consX);
					$cons_credit = 0;
				}
				$data = array(	'act' => 1,
								'crd' => DboSource::expression('NOW()'),
								'fund_id' => $fund,
								'account_id' => $debitid,
								'ledger_date' => $date,
								'trade_date' => $td,
								'settlement_date' => $sd,
								'trade_id' => $tid,
								'trade_crd' => $tcrd,
								'ledger_debit' => $cons_debit,
								'ledger_credit' => $cons_credit,
								'ledger_cfd' => $cfd2,
								'currency_id' => $ccy2,
								'ledger_quantity' => $qty2,
								'sec_id' => $secid2,
								'trinv' => $tr2,
								'ref_id' => $secid);	//this is a ref id holding the original security id (used to tag where cash comes from)
				$this->create($data);
				$this->save();
				
				//second line of double-entry, doing the CREDIT side
				if ($creditid > 1) { 	//cash
					$data['sec_id']  = $this->Currency->getsecid($tccy);
					$data['ledger_quantity'] = $cons;
					$data['trinv'] = '';
					$data['currency_id'] = $tccy;
					$data['ledger_cfd'] = 0;
					//if (($creditid == 2) && ($creditacctype == 'Assets') || ($creditacctype == 'Expenses')) {
						$data['ledger_debit'] =  0;
						$data['ledger_credit'] = abs($cons);
					//}
					//else {
					//	$data['ledger_debit'] = abs($cons);
					//	$data['ledger_credit'] = 0;
					//}
				}
				else {	//special case of stocks
					$data['sec_id']  = $secid;
					$data['ledger_quantity'] = $qty;
					$data['trinv'] = $trinv;
					$data['currency_id'] = $ccy;
					$data['ledger_cfd'] = $cfd;
					////
					$data['ledger_debit'] = 0;
					$data['ledger_credit'] = abs($consX);
				}
				$data['crd'] = DboSource::expression('NOW()');
				$data['account_id'] = $creditid;
				$this->create($data);
				$this->save();
				
				//third line for trading costs
				//trading costs are an expense and so usually should be debited
				if ($trading_costs <> 0) {
					$data['sec_id']  = $this->Currency->getsecid($tccy);
					$data['ledger_quantity'] = 0;
					$data['trinv'] = '';
					$data['crd'] = DboSource::expression('NOW()');
					$data['account_id'] = $trad_cost_acc_id;
					$data['ledger_cfd'] = 0;
					$data['currency_id'] = $tccy;
					////
					$data['ledger_debit'] = abs($trading_costs);
					$data['ledger_credit'] = 0;
					$this->create($data);
					$this->save();
				}
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
	function getPrevPostDate($fund, $date) {
		$fetch = $this->find('first', array('conditions'=>array('Ledger.fund_id ='=>$fund, 'Ledger.ledger_date <'=>$date, 'Ledger.act ='=>1), 'order'=>'Ledger.ledger_date DESC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Ledger']['ledger_date'];
		}
	}
	
	//get date of next ledger posting date, PHP value of 0=false, anything else=true
	function getNextPostDate($fund, $date) {
		$fetch = $this->find('first', array('conditions'=>array('Ledger.fund_id ='=>$fund, 'Ledger.ledger_date >'=>$date, 'Ledger.act ='=>1), 'order'=>'Ledger.ledger_date ASC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Ledger']['ledger_date'];
		}
	}
}

?>