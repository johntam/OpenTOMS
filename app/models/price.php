<?php

class Price extends AppModel {
    var $name = 'Price';
	var $validate = array(
		'price' => array('rule' => 'notEmpty', 'message' => 'Price cannot be blank'),
		'price_source' => array('rule' => 'notEmpty', 'message' => 'Price Source cannot be blank')
	);
	
	
	function get_securities($to, $from, $secfilter, $datefilter, $data) {
	
		if ($datefilter) {
			$conditions=array(
				'Price.price_date =' => $datefilter
			);
		}
		elseif ($secfilter) {
			$conditions=array(
				'Sec.sec_name LIKE' => $secfilter
			);
		}
		else {
			$conditions=array(
				'Price.price_date >=' => date('Y-m-d',strtotime('-'.$from.' weeks')),
				'Price.price_date <=' => date('Y-m-d',strtotime('-'.$to.' weeks'))
			);
		}
		
		//exclude cash from this securities list
		$conditions = array_merge($conditions, array('SecType.sec_type >' => 1));
		
		$params=array(
			'fields' => array('Price.price_date', 'Price.price_source', 'Sec.sec_name', 'Price.price', 'Price.id', 'SecType.sec_type'),
			'joins' => array(
							array('table'=>'secs',
								  'alias'=>'Sec',
								  'type'=>'inner',
								  'foreignKey'=>false,
								  'conditions'=>
										array('Price.sec_id=Sec.id ')
								  ),
							array('table'=>'sec_types',
								  'alias'=>'SecType',
								  'type'=>'inner',
								  'foreignKey'=>false,
								  'conditions'=>
										array('SecType.id=Sec.sec_type_id ')
								  )
							),
			'conditions' => $conditions, //array of conditions
			'order' => array('Price.price_date DESC') //string or array defining order
		);
		
		return($this->find('all', $params));
	}
	
	
	function get_fxrates($pricedate) {
		App::import('model','Sec');
		$sec = new Sec();
		
		$params=array(
			'fields' => array('Price.price_date', 'Price.price_source', 'Sec.sec_name', 'Price.price', 'Price.id', 'SecType.sec_type', 'Price.fx_rate', 'Sec.id', 'Currency.id'),
			'joins' => array(
							array('table'=>'currencies',
								  'alias'=>'Currency',
								  'type'=>'inner',
								  'foreignKey'=>false,
								  'conditions'=>
										array('Sec.currency_id = Currency.id')
								  ),
							array('table'=>'sec_types',
								  'alias'=>'SecType',
								  'type'=>'inner',
								  'foreignKey'=>false,
								  'conditions'=>
										array('Sec.sec_type_id = SecType.id')
								  ),
							array('table'=>'prices',
								  'alias'=>'Price',
								  'type'=>'left',
								  'foreignKey'=>false,
								  'conditions'=>
										array('Price.sec_id = Sec.id',
											  "Price.price_date = '$pricedate'")
								  )
							),
			'conditions' => array(
									'SecType.sec_type ='=> 1
								 ),
			'order' => array('Sec.sec_name') //string or array defining order
		);
		
		$sec->unBindModel(array('belongsTo' => array('SecType','Currency')));
		return($sec->find('all', $params));
	}
	
	function save_fxrates($data) {
		$out = array();
		foreach ($data as $key => $d) {
			$part = split("_",$key);
			$field = $part['0'];
			$id = $part['1'];
			
			//n.b. don't add "fx" as one of the cases below!
			switch ($field) {
				case 'date':
					$out[$id]['date'][] = $d;
					break;
				case 'source':
					$out[$id]['source'][] = $d;
					break;
				case 'price':
					$out[$id]['price'][] = $d;
					break;
				case 'priceid':
					$out[$id]['priceid'][] = $d;
					break;
			}
		}
		
		//save the data to the prices table
		foreach ($out as $key => $o) {
			if (!empty($o['price']['0'])) {
				//check to see if this date is locked for editing
				if (!empty($o['priceid']['0'])) {
					if ($this->islockedID($o['priceid']['0'])) {
						continue;
					}
				}
			
				$this->create(array('Price' => array( 	'id'=>$o['priceid']['0'],
														'crd'=>DboSource::expression('NOW()'),
														'price' => 1,
														'sec_id' => $key,
														'price_source' => $o['source']['0'],
														'price_date' => $o['date']['0'],
														'fx_rate' => $o['price']['0'])));
				$this->save();
			}
		}
	}
	
	//Return row of data for editing
	function get_sec_row($id) {
	
		$params=array(
			'fields' => array('Price.id', 'Price.price_date', 'Price.price_source', 'Sec.sec_name', 'Price.price'),
			'joins' => array(
							array('table'=>'secs',
								  'alias'=>'Sec',
								  'type'=>'inner',
								  'foreignKey'=>false,
								  'conditions'=>
										array('Price.sec_id=Sec.id ')
								  )
							),
			'conditions' => array('Price.id =' => $id)
		);
		
		return($this->find('all', $params));
	}
	
	//Return price of the given security on the given date
	//$date is expected in the standard cakephp array format
	function get_price($sec_id, $date) {
	
		$params=array(
			'fields' => array('Price.price'),
			'conditions' => array( 'Price.sec_id =' => $sec_id,
								   'Price.price_date =' => date('Y-m-d', mktime(0,0,0,$date['month'],$date['day'],$date['year'])))
		);
		$result = $this->find('first', $params);
		
		if (empty($result)) {
			return null;
		}
		else {
			return($result['Price']['price']);
		}
	}
	
	//is this security/date locked? Check using the Balance model.
	function islocked($data) {
		$secid = $data['Price']['sec_id'];
		$month = $data['Price']['price_date']['month'];
		$day = $data['Price']['price_date']['day'];
		$year = $data['Price']['price_date']['year'];
	
		//check if this is a month end, if not then it can't possibly be locked
		$date1 = mktime(0,0,0, $month, $day, $year);
		$date2 = mktime(0,0,0, $month + 1, 0, $year);
		if ($date1 == $date2) {
			App::import('model','Balance');
			$bal = new Balance();
			
			//check if ANY fund has some locked data for this security in the balances table
			$count = $bal->find('count', array('conditions'=>array('Balance.sec_id ='=>$secid, 'Balance.ledger_month ='=>$month, 'Balance.ledger_year ='=>$year, 'Balance.locked ='=>1, 'Balance.act ='=>1)));
			if ($count == 0) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			//not month end
			return false;
		}
	}
	
	//is this security/date locked? Check using the Balance model.
	function islockedID($id) {
		$fetch = $this->find('first', array('conditions'=>array('Price.id ='=>$id)));
		$secid = $fetch['Price']['sec_id'];
		$pricedate = strtotime($fetch['Price']['price_date']);
		$month = date('n', $pricedate);
		$day = date('j', $pricedate);
		$year = date('Y', $pricedate);
	
		//check if this is a month end, if not then it can't possibly be locked
		$date1 = mktime(0,0,0, $month, $day, $year);
		$date2 = mktime(0,0,0, $month + 1, 0, $year);
		if ($date1 == $date2) {
			App::import('model','Balance');
			$bal = new Balance();
			
			//check if ANY fund has some locked data for this security in the balances table
			$count = $bal->find('count', array('conditions'=>array('Balance.sec_id ='=>$secid, 'Balance.ledger_month ='=>$month, 'Balance.ledger_year ='=>$year, 'Balance.locked ='=>1, 'Balance.act ='=>1)));
			if ($count == 0) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			//not month end
			return false;
		}
	}
}

?>