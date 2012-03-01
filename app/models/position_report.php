<?php

class PositionReport extends AppModel {
    var $name = 'PositionReport';
	var $belongsTo ='Fund, Currency, Sec, Account';
	
	
	//combine the trades MTD with the last recorded month end position (from the Balance model)
	//parameters are fund id and date of the position report in UNIX time
	function getPositions($fund, $pdate) {
		//first get the end of last month's balances
		$eolm = mktime(0,0,0, date('n', $pdate), 0, date('Y', $pdate));
		
		//get last month end balances
		App::import('model','Balance');
		$bal = new Balance();
		$baldata = $bal->find('all', array('conditions'=>array('Balance.act =' => 1, 'Balance.ledger_month =' => date('n', $eolm), 'Balance.ledger_year =' => date('Y', $eolm), 'Balance.fund_id =' => $fund)));
		if (empty($baldata)) { return(array('code'=>false, 'err'=>'No month end balances found for previous month')); }
				
		//get this month's MTD trades
		App::import('model','Trade');
		$trade = new Trade();
		$tradesdata = $trade->find('all', array('conditions'=>array('Ledger.act =' => 1, 'Ledger.ledger_month =' => $month, 'Ledger.ledger_year =' => $year, 'Ledger.fund_id =' => $fund)));
		
		//Aggregate these two sets together, GROUP BY (account_id, sec_id)
		$newbal = array();
		foreach ($baldata as $b) {
			$newbal[$b['Balance']['account_id']][$b['Balance']['sec_id']][] = array('ledger_debit'=>$b['Balance']['balance_debit'],
																					'ledger_credit'=>$b['Balance']['balance_credit'],
																					'quantity'=>$b['Balance']['balance_quantity'],
																					'currency_id'=>$b['Balance']['currency_id']);
		}
				
		foreach ($ledgdata as $l) {
			$newbal[$l['Ledger']['account_id']][$l['Ledger']['sec_id']][] = array(  'ledger_debit'=>$l['Ledger']['ledger_debit'],
																					'ledger_credit'=>$l['Ledger']['ledger_credit'],
																					'quantity'=>$l['Ledger']['ledger_quantity'],
																					'currency_id'=>$l['Ledger']['currency_id']);
		}
		
		//deactivate all previous balances for this month end
		$result = $this->updateAll( array('Balance.act' => 0), 
										array(	'Balance.ledger_month =' => $month, 
												'Balance.ledger_year =' => $year, 
												'Balance.fund_id =' => $fund,
												'Balance.locked =' => 0,
												'Balance.act =' => 1));
		
		if (!$result) { return false; }
		//we have a two-dimensional array of aggregated data, save it to the table now
		foreach ($newbal as $acc=>$n1) {
			foreach ($n1 as $sec=>$n2) {
				$totdeb = 0;
				$totcred = 0;
				$totqty = 0;
				$ccy = 0;
				foreach ($n2 as $d) {
					$totdeb += $d['ledger_debit'];
					$totcred += $d['ledger_credit'];
					$totqty += $d['quantity'];
					$ccy = $d['currency_id'];
				}
				$data['Balance'] = array('act' => 1,
										 'locked' => 0,
										 'crd'=>DboSource::expression('NOW()'),
										 'fund_id' => $fund,
										 'account_id'=>$acc,
										 'ledger_month'=>$month,
										 'ledger_year'=>$year,
										 'balance_debit'=>$totdeb,
										 'balance_credit'=>$totcred,
										 'currency_id'=>$ccy,
										 'balance_quantity'=>$totqty,
										 'sec_id'=>$sec);
				$this->create($data);
				$this->save();
			}
		}
		
		return true;
	}
}

?>