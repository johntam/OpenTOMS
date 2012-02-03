<?php

class Sec extends AppModel {
    var $name = 'Sec';
	var $belongsTo = 'SecType,Country,Exchange,Industry,Currency';
	var $validate = array(
		'sec_name' => array('rule' => 'notEmpty', 'message' => 'Security name cannot be blank')
	);
	
	//set the act flag in the model table
	function status($id, $val) {
		$this->id = $id;
		$this->saveField('act', $val);
	}
	
	//See if another security already exists with the same Name, ISIN or Sedol
	//Optional parameter, if passed, will ignore security with that id (used for updating existing security).
	function check_duplicate($data, $id) {
		$name = $data['Sec']['sec_name'];
		$isin = $data['Sec']['isin_code'];
		$sedol = $data['Sec']['sedol'];
		$output = '';
		if ($id) {
			$cond = array('Sec.id <>' => $id);
		}
		else {
			$cond = array();
		}
		
		
		if ($this->find('count', array('conditions'=>array_merge($cond, array('Sec.sec_name =' => $name)))) >0) {
			$output .= 'Name ';
		}
		
		if ($isin) {
			if ($this->find('count', array('conditions'=>array_merge($cond, array('Sec.isin_code =' => $isin)))) >0) {
				$output .= 'ISIN ';
			}
		}
		
		if ($sedol) {
			if ($this->find('count', array('conditions'=>array_merge($cond, array('Sec.sedol =' => $sedol)))) >0) {
				$output .= 'SEDOL ';
			}
		}
		
		return $output;
	}
	
	//determine if the security is a derivative
	function is_deriv($id) {
		$params=array(
			'fields' => array('SecType.cfd'),
			'conditions' => array('Sec.id =' => $id)
		);
		$sectypeid = $this->find('first', $params);

		if ($sectypeid['SecType']['cfd'] == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	//determine if the security is a bond
	function is_bond($id) {
		$params=array(
			'fields' => array('SecType.bond'),
			'conditions' => array('Sec.id =' => $id)
		);
		$sectypeid = $this->find('first', $params);

		if ($sectypeid['SecType']['bond'] == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	//calculate the accrued interest (per 100 nominal)
	function accrued($id, $settdate) {
		//First check if its a bond or not
		if (!$this->is_bond($id)) {
			return(array('code'=>0, 'message'=>'security is not a bond', 'accrued'=>''));
		}
		
		//Need to fetch coupon rate, frequency and calculation method
		$coupon = $this->read('coupon', $id);
		$freq = $this->read('coupon_frequency', $id);
		$method = $this->read('calc_type', $id);
		$coupon_date = $this->read('prev_coupon_date', $id);
		
		$message = null;
		if (!$coupon['Sec']['coupon']) { $message = $message.'coupon,'; }
		if (!$freq['Sec']['coupon_frequency']) { $message = $message.'coupon frequency,'; }
		if (!$method['Sec']['calc_type']) { $message = $message.'calculation type,'; }
		if (!$coupon_date['Sec']['prev_coupon_date']) { $message = $message.'coupon date,'; }
		
		if ($message) {
			//a data item is missing
			return(array('code'=>1, 'message'=>substr($message,0,-1).' missing from this bond', 'accrued'=>0));
		}
		
		//first find out when the last coupon date was
		$date = date_create($coupon_date['Sec']['prev_coupon_date']);
		$sett = date_create($settdate['year'].'-'.$settdate['month'].'-'.$settdate['day']);
		if ($freq['Sec']['coupon_frequency'] == 'ann') {
			$period = 'P1Y';
		}
		elseif ($freq['Sec']['coupon_frequency'] == 'semi') {
			$period = 'P6M';
		}
		elseif ($freq['Sec']['coupon_frequency'] == 'quart') {
			$period = 'P3M';
		}
		else {
			$period = 'P6M';
		}
		
		//loop using the coupon period until we go past the settlement date
		while ($date <= $sett) {
			$date->add(new DateInterval($period));
		}
		
		//roll back one period to come to the last coupon date
		$date->sub(new DateInterval($period));
		
		//number of days accrued
		switch ($method['Sec']['calc_type']) {
			case '30/360':
				$days = $this->datediff30($date, $sett);
				$acc = ($days/360)*$coupon['Sec']['coupon'];
			break;
			
			case '30/365';
				$days = $this->datediff30($date, $sett);
				$acc = ($days/365)*$coupon['Sec']['coupon'];
			break;
		}
		
		return(array('code'=>0, 'accrued'=>$acc));
	}
	
	//Work out the difference between two dates using the 30 day system
	function datediff30($begin, $end) {
		//First check if the dates are in the same month
		if ($begin->format('Y M') == $end->format('Y M')) {
			$diff = min($end->format('d'), 30) - min($begin->format('d'), 30);
		}
		else {
			$diff = 30 - min($begin->format('d'), 30);
			$begin->modify('first day of next month');
			
			//skip each month, adding 30 days to running total
			while ($begin->format('Y M') != $end->format('Y M')) {
				$diff += 30;
				$begin->modify('first day of next month');
			}
			
			//finally adding in number of days in the last month
			$diff += min($end->format('d'), 30);
		}

		return $diff;
	}
}

?>