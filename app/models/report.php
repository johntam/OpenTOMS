<?php

class Report extends AppModel {
    var $name = 'Report';
	var $belongsTo = 'Portfolio, Fund';
	
	function position_report() {	
		$this->loadModel('Portfolio');
		$this->Portfolio->fund_id = $this->fund_id;			
		$trades = $this->Portfolio->get_trades($this->fund_id,$this->start_date,$this->end_date);
		return $trades;
	
	
	
	
	}
}

?>