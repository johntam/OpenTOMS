<?php

class Fund extends AppModel {
    var $name = 'Fund';
	var $belongsTo ='Currency';
	
	function get_fund_ccy($fund_id) {
		$this->id = $fund_id;
		$this->read();
		return($this->data['Fund']['currency_id']);
	}
	
	function get_fund_ccy_name($fund_id) {
		$ccyid = $this->read('currency_id', $fund_id);
		$ccyid = $ccyid['Fund']['currency_id'];
		$ccy = $this->Currency->read('currency_iso_code', $ccyid);
		return($ccy['Currency']['currency_iso_code']);
	}
}

?>