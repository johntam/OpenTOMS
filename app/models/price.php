<?php

class Price extends AppModel {
    var $name = 'Price';
	var $validate = array(
		'price' => array('rule' => 'notEmpty', 'message' => 'Price cannot be blank'),
		'price_source' => array('rule' => 'notEmpty', 'message' => 'Price Source cannot be blank')
	);
	
}

?>