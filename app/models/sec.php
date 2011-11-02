<?php

class Sec extends AppModel {
    var $name = 'Sec';
	var $belongsTo = 'SecType';
	var $validate = array(
		'sec_name' => array('rule' => 'notEmpty', 'message' => 'Security name cannot be blank')
	);
}

?>