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
}

?>