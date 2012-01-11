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
}

?>