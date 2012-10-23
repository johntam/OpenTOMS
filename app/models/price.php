<?php
/*
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class Price extends AppModel {
    var $name = 'Price';
	var $validate = array(
		'price' => array('rule' => 'notEmpty', 'message' => 'Price cannot be blank'),
		'price_source' => array('rule' => 'notEmpty', 'message' => 'Price Source cannot be blank')
	);
	
	
	function get_securities($to, $from, $secfilter, $datefilter, $onlyfunds = false) {
	
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
		
		//do we need to return a list of funds
		if ($onlyfunds) {
			// sectype 10000 is funds
			$conditions = array_merge($conditions, array('SecType.sec_type =' => 10000));
		}
		else {
			$conditions = array_merge($conditions, array('AND' => array('SecType.sec_type >' => 1, 'SecType.sec_type <>' => 10000)));
		}
		
		//create virtual field to take the count of attachments column
		//http://stackoverflow.com/questions/8015845/cakephp-2-0-naming-mysql-aggregate-functions-in-query
		$this->virtualFields += array(
			'NumAttachments' => 0
		);
		
		$params=array(
			'fields' => array('Price.price_date', 'Price.price_source', 'Sec.sec_name', 'Price.price', 
							  'Price.id', 'SecType.sec_type', 'Price.final', 'Sec.id', 'COUNT(Attach.id) AS Price__NumAttachments',
							  'PDQ.yahoo_price','PDQ.yahoo_date','PDQ.google_price','PDQ.google_date','PDQ.bloomberg_price','PDQ.bloomberg_date'),
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
								  ),
							 array('table'=>'attachments',
							  'alias'=>'Attach',
							  'type'=>'left',
							  'foreignKey'=>false,
							  'conditions'=>
									array(	'Price.sec_id=Attach.f_id',
											'Price.price_date=Attach.f_date',
											'Attach.f_table="sec"')
							  ),
							  array('table'=>'pdq_updates',
								  'alias'=>'PDQ',
								  'type'=>'left',
								  'foreignKey'=>false,
								  'conditions'=>
										array('Price.price_date=DATE(PDQ.price_date)',
											  'Price.sec_id=PDQ.sec_id')
								)
							),
			'conditions' => $conditions, //array of conditions
			'group' => array('Price.sec_id','Price.price_date','Attach.f_id','Attach.f_date'),
			'limit' => 100,
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
		$msg='';
		foreach ($out as $key => $o) {
			if (!empty($o['price']['0'])) {
				//check to see if this date is locked for editing
				if (!empty($o['priceid']['0'])) {
					if ($this->islocked($o['priceid']['0'])) {
						$msg = 'Some FX rates have been locked from further editing on this date';
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
		
		if (empty($msg)) {
			$msg='FX rates have been updated';
		}
		
		return $msg;
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
		if (is_array($date)) {
			$pricedate = date('Y-m-d', mktime(0,0,0,$date['month'],$date['day'],$date['year']));
		}
		else {
			$pricedate = $date;
		}
	
		$params=array(
			'fields' => array('Price.price'),
			'conditions' => array( 'Price.sec_id =' => $sec_id,
								   'Price.price_date =' => $pricedate)
		);
		$result = $this->find('first', $params);
		
		if (empty($result)) {
			return null;
		}
		else {
			return($result['Price']['price']);
		}
	}
	
	
	//Return price of the given security on the given date
	function get_fx($ccyid, $date) {
		App::import('model','Currency');
		$currency = new Currency();
		$ccysecid = $currency->getsecid($ccyid);
		
		$fx = $this->find('all', array('conditions'=>array('Price.price_date ='=>$date, 'Price.sec_id ='=>$ccysecid)));
		if (!empty($fx)) {
			return($fx[0]['Price']['fx_rate']);
		}
		else {
			return false;
		}	
	}
	
	
	//write a price to the database. used by the ajax routines on the balance view page and also the fund pricing screen.
	function put_price($sec_id, $date, $price, $fx_rate, $final=1) {		
		if ($this->islocked($sec_id, $date)) {
			return 'Locked';	//locked error code
		}
		else {
			if ($this->check_unique($sec_id, $date)) {
				if (!$fx_rate) {
					$data = array('Price' => array('sec_id'=>$sec_id, 'price_date'=>$date, 'price'=>$price, 'final'=>$final));
				}
				else {
					$data = array('Price' => array('sec_id'=>$sec_id, 'price_date'=>$date, 'price'=>1, 'fx_rate'=>$fx_rate));
				}
				
				if ($this->save($data)) {
					//success
					if (!$fx_rate) {
						return(number_format($price,2));
					}
					else {
						return(number_format($fx_rate,4));
					}
				}
				else {
					return 'Error';	//database write write error code
				}
			}
			else {
				return 'Exists';	//non-unique error code
			}
		}
	}
	
	
	/**
	 *	update a price row in the database, used by the fund pricing screen.
	 **/
	function update_price($sec_id, $date, $id, $price, $final) {		
		if ($this->islocked($sec_id, $date)) {
			return 'Locked';	//locked error code
		}
		else {
				$data = array('Price' => array('id'=>$id, 'price'=>$price, 'final'=>$final));
				
				if ($this->save($data)) {
					//success
					return(number_format($price,2));
				}
				else {
					return 'Error';	//database write error code
				}
				
		}
	}
	
	
	
	//Check to see if a price to be added doesn't break the unique constraint on table prices.
	function check_unique($sec_id, $price_date, $price_source='PDQ') {	
		$conditions=array(
			'Price.price_date =' => $price_date,
			'Price.price_source =' => $price_source,
			'Price.sec_id =' => $sec_id
		);
	
		$params=array(
			'conditions' => $conditions, 
		);
		
		$num = $this->find('count', $params);
		if ($num > 0) { 
			return false;
		} 
		else {
			return true;
		}
	}
	
	//is this security/date locked? Check using the Balance model.
	function islocked($data, $lockdate=null) {
		if (is_array($data)) {
			$secid = $data['Price']['sec_id'];
			$month = $data['Price']['price_date']['month'];
			$day = $data['Price']['price_date']['day'];
			$year = $data['Price']['price_date']['year'];
			$date = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
		}
		else if (!empty($lockdate)) {
			$secid = $data;
			$date = $lockdate;
		}
		else {
			$fetch = $this->find('first', array('conditions'=>array('Price.id ='=>$data)));
			$secid = $fetch['Price']['sec_id'];
			$date = $fetch['Price']['price_date'];
		}

		App::import('model','Balance');
		$bal = new Balance();
		
		//check if ANY fund has some locked data for this security in the balances table
		$count = $bal->find('count', array('conditions'=>array('Balance.sec_id ='=>$secid, 'Balance.balance_date ='=>$date, 'Balance.locked ='=>1, 'Balance.act ='=>1)));
		if ($count == 0) {
			return false;
		}
		else {
			return true;
		}
	}
}

?>
