<?php

class Industry extends AppModel {
    var $name = 'Industry';
	var $validate = array(
		'industry_code' => array('rule' => 'notEmpty', 'message' => 'Code cannot be blank'),
		'industry_name' => array('rule' => 'notEmpty', 'message' => 'Name cannot be blank')
	);
	
}

?>