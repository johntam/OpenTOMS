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
	
	//calculate the accrued interest
	function accrued($id, $settdate) {
		//First check if its a bond or not
		if (!$this->is_bond($id)) {
			return(array('code'=>1, 'message'=>'security is not a bond', 'accrued'=>0));
		}
		
		//Need to fetch coupon rate, frequency and calculation method
		$coupon = $this->read('coupon', $id);
		$freq = $this->read('coupon_frequency', $id);
		$method = $this->read('calc_type', $id);
		$coupon_date = $this->read('prev_coupon_date', $id);
		
		$message = null;
		if (empty($coupon)) { $message = $message.'coupon,'; }
		if (empty($freq)) { $message = $message.'coupon frequency,'; }
		if (empty($method)) { $message = $message.'calculation type,'; }
		if (empty($coupon_date)) { $message = $message.'coupon date,'; }
		if (!$message) {
			//a data item is missing
			return(array('code'=>1, 'message'=>substr($message,0,-1).' missing from this security', 'accrued'=>0));
		}
		
		//first find out when the last coupon date was
		$date = new DateTime($coupon_date);
		
		
		
		$date->add(new DateInterval('P10D'));
		echo $date->format('Y-m-d') . "\n";
		
	}
}

?>