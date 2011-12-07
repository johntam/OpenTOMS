<?php

class Price extends AppModel {
    var $name = 'Price';
	var $validate = array(
		'price' => array('rule' => 'notEmpty', 'message' => 'Price cannot be blank'),
		'price_source' => array('rule' => 'notEmpty', 'message' => 'Price Source cannot be blank')
	);
	
	function get_fxrates($pricedate) {
		App::import('model','Sec');
		$sec = new Sec();
		
		$params=array(
			'fields' => array('Price.price_date', 'Price.price_source', 'Sec.sec_name', 'Price.price', 'Price.id', 'SecType.sec_type', 'Sec.id'),
			'joins' => array(
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
		
		$sec->unBindModel(array('belongsTo' => array('SecType')));
		return($sec->find('all', $params));
	}
	
	function save_fxrates($data) {
		$out = array();
		foreach ($data as $key => $d) {
			$part = split("_",$key);
			$field = $part['0'];
			$id = $part['1'];
			
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
														'price' => $o['price']['0'],
														'sec_id' => $key,
														'price_source' => $o['source']['0'],
														'price_date' => $o['date']['0'])));
				$this->save();
			}
		}
	}
}

?>