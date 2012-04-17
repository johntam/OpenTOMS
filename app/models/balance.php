<?php

class Balance extends AppModel {
    var $name = 'Balance';
	var $belongsTo ='Account, Currency, Fund, Sec, Custodian';
	
	//calculate the month end balances, using the last month end balances and this month's general ledger
	function calc($fund, $date) {
		//first get the date when the last balance was calculated.
		$prevdate = $this->getPrevBalanceDate($fund, $date);
				
		//get the last balance data, else use a null array
		if (empty($prevdate)) {
			$baldata = array();
		}
		else {
			$baldata = $this->find('all', array('conditions'=>array('Balance.act =' => 1, 
																	'Balance.balance_date =' => $prevdate, 
																	'Balance.fund_id =' => $fund)));
		}
				
		//get this month's ledger entries
		App::import('model','Ledger');
		$ledger = new Ledger();
		$ledgdata = $ledger->find('all', array('conditions'=>array('Ledger.act =' => 1, 
																   'Ledger.ledger_date =' => $date, 
																   'Ledger.fund_id =' => $fund,
																   'Ledger.sec_id >' => 0), 
											   'order'=>array('Ledger.trade_crd ASC')));
		
		//Aggregate these two sets together, GROUP BY (custodian_id, account_id, sec_id)
		$newbal = array();
		foreach ($baldata as $b) {
			$newbal[$b['Balance']['custodian_id']][$b['Balance']['account_id']][$b['Balance']['sec_id']][] = array('ledger_debit'=>$b['Balance']['balance_debit'],
																					'ledger_credit'=>$b['Balance']['balance_credit'],
																					'quantity'=>$b['Balance']['balance_quantity'],
																					'currency_id'=>$b['Balance']['currency_id'],
																					'cfd'=>$b['Balance']['balance_cfd'],
																					'trinv'=>$b['Balance']['trinv'],
																					'unsettled'=>$b['Balance']['unsettled']);
		}
				
		foreach ($ledgdata as $l) {
			$newbal[$l['Ledger']['custodian_id']][$l['Ledger']['account_id']][$l['Ledger']['sec_id']][] = array(  'ledger_debit'=>$l['Ledger']['ledger_debit'],
																					'ledger_credit'=>$l['Ledger']['ledger_credit'],
																					'quantity'=>$l['Ledger']['ledger_quantity'],
																					'currency_id'=>$l['Ledger']['currency_id'],
																					'cfd'=>$l['Ledger']['ledger_cfd'],
																					'trinv'=>$l['Ledger']['trinv'],
																					'trade_date'=>$l['Ledger']['trade_date'],
																					'trade_id'=>$l['Ledger']['trade_id'],
																					'settlement_date'=>$l['Ledger']['settlement_date']);
		}		

		$newbal = $this->sortByAccountID($newbal);	//make sure that the stock book (id=1) is the first to be processed, throw pnl off to the cash book below
		
		//deactivate all previous balances for this month end
		$result = $this->updateAll( array('Balance.act' => 0), 
										array(	'Balance.balance_date =' => $date,
												'Balance.fund_id =' => $fund,
												'Balance.locked =' => 0,
												'Balance.act =' => 1));
		
		if (!$result) { return false; }
		
		$pnl_acc__id = $this->Account->getNamed('Profit And Loss');
		$cash_acc_id = $this->Account->getNamed('Cash');
		$accrued_acc_id = $this->Account->getNamed('Accrued Interest');
		$stocks_acc_id = $this->Account->getNamed('Stocks');
		
		//we have a three-dimensional array of aggregated data, save it to the table now
		foreach ($newbal as $cust=>&$n0) {
			foreach ($n0 as $acc=>$n1) {
				foreach ($n1 as $sec=>$n2) {
					$totdeb = 0;
					$totcred = 0;
					$totqty = 0;
					$ccy = 0;
					$pnl = 0;
					$trinv = '';
					$ref_id = '';
					$unsettled = '';
					foreach ($n2 as $d) {
						$totdeb += $d['ledger_debit'];
						$totcred += $d['ledger_credit'];
						$totqty += $d['quantity'];
						$ccy = $d['currency_id'];
						$cfd = $d['cfd'];
						$tri = $d['trinv'];
						if (isset($d['ref_id'])) {
							$ref_id .= $d['ref_id'];
							if ($cfd == 1) {
								$unsettled .= $d['ref_id'];	//remove the settled trades later on
							}
						}
						if (isset($d['trade_date'])) {
							$td = $d['trade_date'];
						}
						else {
							$td = null;
						}
						if (isset($d['trade_id'])) {
							$tid = $d['trade_id'];
						}
						else {
							$tid = null;
						}
						if (isset($d['settlement_date'])) {
							$sd = $d['settlement_date'];
						}
						else {
							$sd = null;
						}
						if (isset($d['unsettled'])) {
							$unsettled .= $d['unsettled'];
						}
						
						
						//only work out realised P&L for securities, not cash
						if ($acc == $stocks_acc_id) {
							$result = $this->fifo($trinv, $tri);
							$pnl = $result[0];
							$trinv = $result[1];
							
							//process any PnL thrown off this security this month
							if ($cfd) {
								//for cfd types need to add the pnl to cash, double-entry to the opposite side in the PnL account
								
								//also, for the use of the cash ledger screen, for the benefit of other functions, record the trade details in the ref_id column under the Profit and Loss Account entries
								//the format of the ref_id "atoms" are sec_id:cfd:debit amt:credit amt:quantity;
								if ($pnl > 0) {
									$newbal[$cust][$cash_acc_id][$this->Currency->getsecid($ccy)][]=array('ledger_debit'=>$pnl,
																							 'ledger_credit'=>0,
																							 'quantity'=>$pnl,
																							 'currency_id'=>$ccy,
																							 'cfd'=>$cfd,
																							 'trinv'=>'');
									$newbal[$cust][$pnl_acc__id][$this->Currency->getsecid($ccy)][]=array('ledger_debit'=>0,
																								 'ledger_credit'=>$pnl,
																								 'quantity'=>0,
																								 'currency_id'=>$ccy,
																								 'cfd'=>$cfd,
																								 'trinv'=>'',
																								 'ref_id'=> $sec.':'.$cfd.':'.$tid.':'.$td.':'.'0'.':'.$pnl.':'.$pnl.':'.$sd.':'.$cust.';');
								}
								else if ($pnl < 0) {
									$newbal[$cust][$cash_acc_id][$this->Currency->getsecid($ccy)][]=array('ledger_debit'=>0,
																							 'ledger_credit'=>abs($pnl),
																							 'quantity'=>$pnl,
																							 'currency_id'=>$ccy,
																							 'cfd'=>$cfd,
																							 'trinv'=>'');
									$newbal[$cust][$pnl_acc__id][$this->Currency->getsecid($ccy)][]=array('ledger_debit'=>abs($pnl),
																								 'ledger_credit'=>0,
																								 'quantity'=>0,
																								 'currency_id'=>$ccy,
																								 'cfd'=>$cfd,
																								 'trinv'=>'',
																								 'ref_id'=> $sec.':'.$cfd.':'.$tid.':'.$td.':'.abs($pnl).':'.'0'.':'.$pnl.':'.$sd.':'.$cust.';');
								}
							}
							else {
								//for non-cfd types need to add pnl back to security line, double-entry to the opposite side in the PnL account
								if ($pnl > 0) {
									$totdeb += $pnl;
									$newbal[$cust][$pnl_acc__id][$this->Currency->getsecid($ccy)][]=array('ledger_debit'=>0,
																								 'ledger_credit'=>$pnl,
																								 'quantity'=>0,
																								 'currency_id'=>$ccy,
																								 'cfd'=>$cfd,
																								 'trinv'=>'',
																								 'ref_id'=> $sec.':'.$cfd.':'.$tid.':'.$td.':'.'0'.':'.$pnl.':'.$pnl.':'.$sd.':'.$cust.';');
								}
								else if ($pnl < 0) {
									$totcred += abs($pnl);
									$newbal[$cust][$pnl_acc__id][$this->Currency->getsecid($ccy)][]=array('ledger_debit'=>abs($pnl),
																								 'ledger_credit'=>0,
																								 'quantity'=>0,
																								 'currency_id'=>$ccy,
																								 'cfd'=>$cfd,
																								 'trinv'=>'',
																								 'ref_id'=> $sec.':'.$cfd.':'.$tid.':'.$td.':'.abs($pnl).':'.'0'.':'.$pnl.':'.$sd.':'.$cust.';');
								}
							}
						}
					}
					
					
					//For the unsettled field entry, remove any trades that have settled this month.
					//This field is used by the CashLedger model to add back in any unsettled trades into
					//the cash figures.
					$still_unsettled = '';
					$sp1 = explode(";", $unsettled);
					foreach ($sp1 as $sp2) {
						if (!empty($sp2)) {
							$sp3 = explode(':', "$sp2::::::::");
							if (strtotime($sp3[7]) > strtotime($date)) {	//$sp3[7] is the settlement date
								$still_unsettled .= $sp2.';';
							}
						}
					}
					
					//write this result line to the database, only if the position is non-zero though
					if (!(($acc == $stocks_acc_id) && ($totqty == 0) && (abs($totdeb - $totcred) < 0.01))) {		
						$data['Balance'] = array('act' => 1,
												 'locked' => 0,
												 'crd'=>DboSource::expression('NOW()'),
												 'fund_id' => $fund,
												 'account_id'=>$acc,
												 'custodian_id'=>$cust,
												 'balance_date'=>$date,
												 'balance_debit'=>$totdeb,
												 'balance_credit'=>$totcred,
												 'balance_cfd'=>$cfd,
												 'currency_id'=>$ccy,
												 'balance_quantity'=>$totqty,
												 'sec_id'=>$sec,
												 'trinv'=>$trinv,
												 'ref_id'=>$ref_id,
												 'unsettled'=>$still_unsettled);
						$this->create($data);
						$this->save();
					}
					
					
					//if this is a bond, add in accrued interest which should be calculated from the last
					//balance calculation date to the journal posting date
					//do a double-entry, one for stocks account and one for accrued interest account
					if (($acc == $stocks_acc_id) && ($totqty <> 0)) {
						$result = $this->Sec->accrued($sec, $date);
						if (($result['code'] == 0) && (!empty($result['accrued']))) {
							$data['Balance'] = array('act' => 1,
													 'locked' => 0,
													 'crd'=>DboSource::expression('NOW()'),
													 'fund_id' => $fund,
													 'custodian_id'=>$cust,
													 'balance_date'=>$date,
													 'balance_cfd'=>0,
													 'currency_id'=>$ccy,
													 'sec_id'=>$sec);
							
							//test to see if need to debit or credit depending if long or short
							if ($totqty < 0) {
								$db = 0;
								$cr = $result['accrued'] * abs($totqty) / 100;
							}
							else {
								$db = $result['accrued'] * abs($totqty) / 100;
								$cr = 0;
							}
							
							//the stocks account
							$data['Balance']['account_id'] = $stocks_acc_id;
							$data['Balance']['balance_debit'] = $db;
							$data['Balance']['balance_credit'] = $cr;
							$data['Balance']['balance_quantity'] = 0;
							$this->create($data);
							$this->save();
							
							//the accrued interest account
							$data['Balance']['account_id'] = $accrued_acc_id;
							$data['Balance']['balance_debit'] = $cr;
							$data['Balance']['balance_credit'] = $db;
							$data['Balance']['balance_quantity'] = 0;
							$this->create($data);
							$this->save();
						}
					}
				}
			}
		}
		return true;
	}
	
	
	//put prices and fx rates next to balance items by left joining onto the prices table
	function attachprices($fund, $date) {
		$this->unBindModel(array('belongsTo' => array('Currency')));
		
		$params=array(	'fields' => array(	'Fund.fund_name',
											'Account.id',
											'Account.account_name',
											'Custodian.custodian_name',
											'Balance.balance_debit',
											'Balance.balance_credit',
											'Currency.currency_iso_code',
											'Sec.sec_name',
											'Balance.balance_quantity',
											'Price.price',
											'Price.fx_rate',	//used for cash securities in stocks account
											'PriceFX.fx_rate',
											'Price.sec_id',
											'Balance.sec_id',
											'Sec.id',
											'Currency.sec_id',
											'Sec.sec_type_id',
											'Balance.currency_id',
											'Sec.valpoint',
											'SecType.cfd',
											'Balance.trinv'),
						'joins' => array(
										array('table'=>'currencies',
											  'alias'=>'Currency',
											  'type'=>'inner',
											  'foreignKey'=>false,
											  'conditions'=>
													array(	'Currency.id=Balance.currency_id')
											  ),
										array('table'=>'prices',
											  'alias'=>'Price',
											  'type'=>'left',
											  'foreignKey'=>false,
											  'conditions'=>
													array(	'Price.sec_id=Balance.sec_id',
															"Price.price_date='".$date."'")
											  ),
										array('table'=>'prices',
											  'alias'=>'PriceFX',
											  'type'=>'left',
											  'foreignKey'=>false,
											  'conditions'=>
													array(	'PriceFX.sec_id=Currency.sec_id',
															"PriceFX.price_date='".$date."'")
											  ),
										array('table'=>'secs',
											  'alias'=>'Sec2',
											  'type'=>'inner',
											  'foreignKey'=>false,
											  'conditions'=>
													array(	'Balance.sec_id=Sec2.id')
											  ),
										array('table'=>'sec_types',
											  'alias'=>'SecType',
											  'type'=>'inner',
											  'foreignKey'=>false,
											  'conditions'=>
													array(	'Sec2.sec_type_id=SecType.id')
											  )
										),
						'conditions' => array('Balance.act ='=>1, 'Balance.fund_id ='=>$fund, 'Balance.balance_date ='=>$date),
						'order' => array('Balance.custodian_id'=>'ASC', 'Balance.account_id'=>'ASC')
					);		
		
		return ($this->find('all', $params));
	}
	
	
	//check to make sure that the fund currency is included in a balance list of securities, or that it has already been given an fx rate for this date
	function hasfundccy($fund, $date, $balances) {	
		$fundccyid = $this->Fund->read('currency_id', $fund);
			$fundccyid = $fundccyid['Fund']['currency_id'];
		$fundccyname = $this->Currency->read('currency_iso_code', $fundccyid);
			$fundccyname = $fundccyname['Currency']['currency_iso_code'];
		$fundccysecid = $this->Currency->read('sec_id', $fundccyid);
			$fundccysecid = $fundccysecid['Currency']['sec_id'];
		
		$found = false;
		
		//first check price table
		App::import('model','Price');
		$pricemodel = new Price();
		$price = $pricemodel->get_price($fundccysecid, $date);
		
		if (empty($price)) {
			//not in price table so see if its in the balance list (where the user will be asked to input a price for it)
			foreach ($balances as $b) {
				if ($b['Sec']['sec_name'] == $fundccyname) {
					$found = true;
					break;
				}
			}
		}
		else {
			$found = true;
		}
		
		return (array($found, $fundccysecid, $fundccyname));
	}
	
	
	//lock month end
	function lock($fund, $date) {
		$result = $this->updateAll( array('Balance.locked' => 1), 
										array(	'Balance.balance_date =' => $date,
												'Balance.fund_id =' => $fund,
												'Balance.act =' => 1));
		return ($result);
	}
	
	//unlock month end
	function unlock($fund, $date) {
		$result = $this->updateAll( array('Balance.locked' => 0), 
										array(	'Balance.balance_date =' => $date, 
												'Balance.fund_id =' => $fund,
												'Balance.act =' => 1));
		//unlock all future month ends
		$result2 = $this->updateAll( array('Balance.locked' => 0), 
										array(	'Balance.balance_date >' => $date, 
												'Balance.fund_id =' => $fund,
												'Balance.act =' => 1));
		return ($result && $result2);
	}
	
	//is this month locked?
	//the better way would be to have a record dates table with a locked status field, maybe do this for a future version.
	function islocked($fund, $date) {
		$result = $this->find('first', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.balance_date ='=>$date, 'Balance.act ='=>1), 'fields'=>array('Balance.locked')));
		
		if (empty($result['Balance']['locked'])) {
			return false;
		}
		else if ($result['Balance']['locked'] == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	
	
	//clear all the balance data for this fund
	//!Warning, use with extreme caution!
	function wipe($fund) {
		$result = $this->updateAll( array('Balance.locked' => 0,
										  'Balance.act' => 0), 
										array(	'Balance.fund_id =' => $fund));
		return $result;
	}
	
	
	//get the previous balance date for the fund, PHP value of 0=false, anything else=true
	function getPrevBalanceDate($fund, $date) {
		$fetch = $this->find('first', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.balance_date <' => $date, 'Balance.act ='=>1), 'order'=>'Balance.balance_date DESC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Balance']['balance_date'];
		}
	}
	
	//get the next balance date for the fund, PHP value of 0=false, anything else=true
	function getNextBalanceDate($fund, $date) {
		$fetch = $this->find('first', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.balance_date >' => $date, 'Balance.act ='=>1), 'order'=>'Balance.balance_date ASC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Balance']['balance_date'];
		}
	}
	
	//get date of last locked balance date, PHP value of 0=false, anything else=true
	function getPrevLockedDate($fund) {
		$fetch = $this->find('first', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.locked =' => 1, 'Balance.act ='=>1), 'order'=>'Balance.balance_date DESC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Balance']['balance_date'];
		}
	}
	
	
	//check to see if there is a balance calculation for the given date
	function balanceExists($fund, $date) {
		$count = $this->find('count', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.balance_date =' => $date, 'Balance.act ='=>1)));
		if ($count > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	
	
	//check to see if a more recent journal posting has happened since a balance calculation
	function needsRecalc($fund, $date) {
		App::import('model','Ledger');
		$ledger = new Ledger();
		
		$lcrd = $ledger->find('first', array('conditions'=>array('Ledger.fund_id ='=>$fund, 'Ledger.ledger_date =' => $date, 'Ledger.act ='=>1)));
		if (empty($lcrd)) {
			return false;	//no journal for this date so balance calc must be up to date
		}
		else {
			$lcrd = strtotime($lcrd['Ledger']['crd']);
		}
	
		$bcrd = $this->find('first', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.balance_date =' => $date, 'Balance.act ='=>1)));
		if (empty($bcrd)) {
			return true;	//no balance records found, but a journal posting exists for this date, ergo balance not up to date
		}
		else {
			$bcrd = strtotime($bcrd['Balance']['crd']);
			if ($bcrd > $lcrd) {
				return false;
			}
			else {
				return true;
			}
		}
	
	}
	
	
	//this function processes a new trade against a base sequence of historic trades using the fifo convention
	function fifo($base, $new) {
		$b = $this->decode($base);
		$n = $this->decode($new);
		ksort($b);
		ksort($n);
		
		$pnl = 0;
		foreach ($n as $dt=>$m) {
			$qty = $m['quantity'];
			$pr = $m['price'];
			$vp = $m['valpoint'];
		
			//go through each segment of the trade history and see if the new trade could be offset against it or not
			//N.B. if the new trade is a buy (+ve quantity), then look for previous sells (-ve quantity) to offset against and vice versa
			foreach ($b as $date=>$c) {
				$qtyp = $c['quantity'];
				$prp = $c['price'];
				
				//echo debug(array('status'=>'before\\\\\\\\\\\\\\\\\\\\\\\\\\','qty'=>$qty, 'pr'=>$pr, 'vp'=>$vp, 'qtyp'=>$qtyp, 'prp'=>$prp, 'pnl'=>$pnl));
				
				if ($qty * $qtyp < 0) {
					if (abs($qty) > abs($qtyp)) {
						if ((($qty < 0) && ($pr >= $prp)) || (($qty > 0) && ($pr < $prp))) {
							$pnl = $pnl + abs($qtyp)*abs($pr-$prp)*$vp;
						}
						else {
							$pnl = $pnl - abs($qtyp)*abs($pr-$prp)*$vp;
						}
						$qty = $qty + $qtyp;
						unset($b[$date]);
					}
					else if (abs($qty) == abs($qtyp)) {
						if ((($qty < 0) && ($pr >= $prp)) || (($qty > 0) && ($pr < $prp))) {
							$pnl = $pnl + abs($qty)*abs($pr-$prp)*$vp;
						}
						else {
							$pnl = $pnl - abs($qty)*abs($pr-$prp)*$vp;
						}
						$qty = 0;
						unset($b[$date]);
						break;
					}
					else {
						if ((($qty < 0) && ($pr >= $prp)) || (($qty > 0) && ($pr < $prp))) {
							$pnl = $pnl + abs($qty)*abs($pr-$prp)*$vp;
						}
						else {
							$pnl = $pnl - abs($qty)*abs($pr-$prp)*$vp;
						}
						$b[$date]['quantity'] = $qtyp + $qty;
						$qty = 0;
						break;
					}
				}
				
				//echo debug(array('status'=>'atfer\\\\\\\\\\\\\\\\\\\\\\\\\\','qty'=>$qty, 'pr'=>$pr, 'vp'=>$vp, 'qtyp'=>$qtyp, 'prp'=>$prp, 'pnl'=>$pnl));
				
			}
			
			if ($qty <> 0) {
				$b = $b + array($dt=>array('quantity'=>$qty, 'price'=>$pr, 'valpoint'=>$vp));
			}
		}
		return array($pnl, $this->encode($b));
	}
	
	//encodes a working array into the "Trinv" standard format
	function encode($tr) {
		$trinv = '';
		foreach ($tr as $date=>$t) {
			$trinv = $trinv.$date.':'.$t['quantity'].':'.$t['price'].':'.$t['valpoint'].';';
		}
		return $trinv;
	}
	
	//converts a string in the "Trinv" format into an array for easier processing
	function decode($tr) {
		$arr = array();
		$sp1 = explode(";", $tr);
		foreach ($sp1 as $sp2) {
			if (!empty($sp2)) {
				$sp3 = explode(':', "$sp2:::");
				$arr[$sp3[0]] = array('quantity'=>$sp3[1], 'price'=>$sp3[2], 'valpoint'=>$sp3[3]);
			}
		}
		return $arr;
	}
	
	//converts a string in the "ref_id" format into an array
	//used by the CashLedger controller
	function decodeRefID($ref_id) {
		$arr = array();
		$sp1 = explode(";", $ref_id);
		foreach ($sp1 as $sp2) {
			if (!empty($sp2)) {
				$sp3 = explode(':', "$sp2::::::::");
				$arr[] = array(	'sec_id'=>$sp3[0],
								'cfd'=>$sp3[1],
								'trade_id'=>$sp3[2],
								'trade_date'=>$sp3[3],
								'debit'=>$sp3[4], 
								'credit'=>$sp3[5], 
								'quantity'=>$sp3[6],
								'settlement_date'=>$sp3[7],
								'custodian_id'=>$sp3[8]);
			}
		}
		return $arr;
	}
	
	
	//sort the multidimensional array $newbal by the second dimension, ie account_id
	function sortByAccountID(array $array) {
		$result = array();
		foreach ($array as $cust => $value) {
			$value2 = $value;
			ksort($value2);
			$result[$cust] = $value2;
		}
		   
		return $result;
	}
}

?>