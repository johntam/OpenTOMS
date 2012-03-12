<?php

class Sec extends AppModel {
    var $name = 'Sec';
	var $belongsTo = 'SecType,Country,Exchange,Industry,Currency';
	var $validate = array(
		'sec_name' => array('rule' => 'notEmpty', 'message' => 'Security name cannot be blank'),
		'valpoint' => array('rule' => 'notEmpty', 'message' => 'Valpoint cannot be blank')
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
	
	//determine if the security is an equity
	function is_equity($id) {
		$params=array(
			'fields' => array('SecType.equity'),
			'conditions' => array('Sec.id =' => $id)
		);
		$sectypeid = $this->find('first', $params);

		if ($sectypeid['SecType']['equity'] == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	//determine if the security is cash
	function is_cash($id) {
		$result = $this->Currency->find('first', array('conditions'=>array('Currency.sec_id ='=>$id)));
		if (empty($result)) {
			return false;
		}
		else {
			return true;
		}
	}
	
	//calculate the accrued interest (per 100 nominal)
	function accrued($id, $settdate) {
		//First check if its a bond, but not a bond future
		if (!$this->is_bond($id) || $this->is_deriv($id)) {
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
		$date = strtotime($coupon_date['Sec']['prev_coupon_date']);
		$sett = mktime(0,0,0,$settdate['month'],$settdate['day'],$settdate['year']);
		if ($freq['Sec']['coupon_frequency'] == 'ann') {
			$period = '+1 Year';
		}
		elseif ($freq['Sec']['coupon_frequency'] == 'semi') {
			$period = '+6 Months';
		}
		elseif ($freq['Sec']['coupon_frequency'] == 'quart') {
			$period = '+3 Months';
		}
		else {
			$period = '+6 Months';
		}
		
		//loop using the coupon period until we go past the settlement date
		while ($date <= $sett) {
			$lastdate = $date;
			$date =  strtotime($period, $date);
		}
		
		//roll back one period to come to the last coupon date
		$date=$lastdate;
		
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
	//both parameters are Unix timestamps.
	function datediff30($begin, $end) {
		//First check if the dates are in the same month
		if (date('Y M', $begin) == date('Y M',$end)) {
			$diff = min(date('d',$end), 30) - min(date('d',$begin), 30);
		}
		else {
			$diff = 30 - min(date('d',$begin), 30);
			$begin = strtotime('first month',strtotime(date('Y-m-01', $begin)));
			//$begin->modify('first day of next month'); only works in PHP 5.3+
			
			//skip each month, adding 30 days to running total
			while (date('Y M',$begin) != date('Y M',$end)) {
				$diff += 30;
				$begin = strtotime('first month',strtotime(date('Y-m-01', $begin)));
			}
			
			//finally adding in number of days in the last month
			$diff += min(date('d',$end), 30);
		}

		return $diff;
	}
}

?>