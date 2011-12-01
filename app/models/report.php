<?php

class Report extends AppModel {
    var $name = 'Report';
	var $belongsTo = 'Portfolio, Fund';
	var $prev_report_id;
	
	function position_report() {
		$this->Portfolio->report_id = $this->report_id;
		$this->Portfolio->fund_id = $this->fund_id;
		$this->Portfolio->port_type = 'stock';
		$this->Portfolio->end_date = $this->end_date;
		
		if ($this->calc_start_date == null) {
			$this->Portfolio->start_date = '1999-12-31';
			$this->Portfolio->create_portfolio(1);
		}
		else {
			$this->Portfolio->start_date = $this->calc_start_date;
			$this->Portfolio->prev_report_id = $this->prev_report_id;
			$this->Portfolio->create_portfolio(2);
		}
		
		$this->Portfolio->save_portfolio();		
		return($this->Portfolio->portfolio);
	}
	
	//save report metadata in the reports table
	function save_report() {
		$this->create(array('Report' => array(  'act'=>1,
												'crd'=>DboSource::expression('NOW()'),
												'report_type' => $this->report_type,
												'run_date' => $this->end_date,
												'fund_id' => $this->fund_id,
												'calc_start_date' => $this->calc_start_date)));
		$this->save();
		return $this->id;
	}
	
	//find if a an active report for this report type and the specified run-date exists already
	function get_prev_report() {
		$this->recursive = -1;
		$params=array(
			'conditions' => array(  'Report.act =' => 1, 
									'Report.report_type =' => $this->report_type, 
									'Report.fund_id =' => $this->fund_id),
			'order' => array('Report.run_date DESC')
		);
		
		$latest = $this->find('first', $params);
		return(array('run_date'=>$latest['Report']['run_date'], 'id'=>$latest['Report']['id']));
	}
	
	//fetch the whole data for a report run previously
	function get_prev_report_data() {
		$this->recursive = -1;
		$data = $this->Portfolio->find('all', array('conditions'=>array('Portfolio.report_id ='=>$this->report_id)));
		
	
	
	}
}

?>