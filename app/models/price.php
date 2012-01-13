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
}

?>