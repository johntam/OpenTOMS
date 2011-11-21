<?php

class Report extends AppModel {
    var $name = 'Report';
	var $belongsTo = 'Portfolio, Fund';
	
	function position_report() {	
		//Save report metadata first to get the report id
		$this->Portfolio->report_id = $this->report_id;
		$this->Portfolio->fund_id = $this->fund_id;
		$this->Portfolio->port_type = 'stock';
		$this->Portfolio->start_date = $this->start_date;
		$this->Portfolio->end_date = $this->end_date;
		
		$this->Portfolio->get_trades();
		$this->Portfolio->save_portfolio();
		return($this->Portfolio->portfolio);
	}
	
	//save report metadata in the reports table
	function save_report() {
		$this->create(array('Report' => array(  'act'=>1,
												'crd'=>DboSource::expression('NOW()'),
												'report_type' => $this->report_type,
												'run_date' => $this->end_date,
												'fund_id' => $this->fund_id)));
		$this->save();
		return $this->id;
	}
	
	//find if a an active report for this report type and the specified run-date exists already
	function check_prev() {
		$this->recursive = -1;
		$params=array(
			'conditions' => array(  'Report.act =' => 1, 
									'Report.report_type =' => $this->report_type, 
									'Report.run_date =' => $this->end_date, 
									'Report.fund_id =' => $this->fund_id)
		);
		
		if ($this->find('count', $params) == 0) {
			return false;
		}
		else {
			return true;
		};
	}
}

?>