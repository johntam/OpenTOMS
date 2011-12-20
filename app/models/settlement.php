<?php

class Settlement extends AppModel {
    var $name = 'Settlement';
	var $belongsTo = 'Country,SecType';
}

?>