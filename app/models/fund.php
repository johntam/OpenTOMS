<?php

class Fund extends AppModel {
    var $name = 'Fund';
	
	function get_fund_ccy($fund_id) {
		$this->id = $fund_id;
		$this->read();
		return($this->data['Fund']['currency_id']);
	}
}

?>