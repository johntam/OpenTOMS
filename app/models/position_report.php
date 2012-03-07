<?php

class PositionReport extends AppModel {
    var $name = 'PositionReport';
	var $belongsTo ='Fund, Currency, Sec, Account';
	
	
	//combine latest trades with the last recorded locked balance (from the Balance model)
	function getPositions($fund, $date) {
		App::import('model','Balance');
		$balmodel = new Balance();
	
		//first get the date when the last balance was calculated.
		$prevdate = $balmodel->getPrevLockedDate($fund);
				
		//get the last balance data, else use a null array
		if (empty($prevdate)) {
			$baldata = array();
		}
		else {
			$baldata = $this->find('all', array('conditions'=>array('Balance.act =' => 1, 'Balance.balance_date =' => $prevdate, 'Balance.fund_id =' => $fund)));
		}
				
		//get this month's ledger entries
		App::import('model','Ledger');
		$ledger = new Ledger();
		$ledgdata = $ledger->find('all', array('conditions'=>array('Ledger.act =' => 1, 'Ledger.ledger_date =' => $date, 'Ledger.fund_id =' => $fund)));
		
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
										array(	'Balance.balance_date =' => $date,
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
										 'balance_date'=>$date,
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