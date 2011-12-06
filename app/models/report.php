<?php

class Report extends AppModel {
    var $name = 'Report';
	var $prev_report_id;
	
	function position_report() {
		App::import('model','Portfolio');
		$port = new Portfolio();
		
		$port->report_id = $this->id;
		$port->fund_id = $this->fund_id;
		$port->portfolio_type = 'stock';
		$port->run_date = $this->run_date;
		
		if ($this->calc_start_date == null) {
			$port->calc_start_date = '1999-12-31';
			$port->create_portfolio(1);
		}
		else {
			$port->calc_start_date = $this->calc_start_date;
			$port->prev_report_id = $this->prev_report_id;
			$port->create_portfolio(2);
		}
		
		$port->save_portfolio();		
		return($port->portfolio);
	}
	
	function nav_report() {
	
	
	
	}
	
	//save report metadata in the reports table
	function save_report() {
		$this->create(array('Report' => array(  'act'=>1,
												'crd'=>DboSource::expression('NOW()'),
												'report_type' => $this->report_type,
												'run_date' => $this->run_date,
												'fund_id' => $this->fund_id,
												'calc_start_date' => $this->calc_start_date)));
		$this->save();
		return $this->id;
	}
	
	//find if a an active report for this report type and the specified run-date exists already
	function get_prev_report() {
		$params=array(
			'conditions' => array(  'Report.act =' => 1, 
									'Report.report_type =' => $this->report_type, 
									'Report.run_date <=' => $this->run_date,	//ignore future reports from this run date
									'Report.fund_id =' => $this->fund_id),
			'order' => array('Report.run_date DESC')
		);
		
		$latest = $this->find('first', $params);
		return(array('run_date'=>$latest['Report']['run_date'], 'id'=>$latest['Report']['id']));
	}
	
	//fetch the whole data for a report run previously
	function get_prev_report_data() {
		App::import('model','Portfolio');
		$port = new Portfolio();
		$data = $port->find('all', array('conditions'=>array('Portfolio.report_id ='=>$this->report_id)));
		
		$portfolio = array();
		foreach ($data as $d) {
			$portfolio[] = array('0'=>array('quantity'=>$d['Portfolio']['position']),
										 'Sec'=>array('sec_name'=>$d['Portfolio']['sec_name'],
													  'id'=>$d['Portfolio']['sec_id']));
		}		
		return $portfolio;
	}
	
	//deactivate all previous reports with the given run_date
	//uses updateAll(array $fields, array $conditions)
	//NB assumes that run_date and fund_id have been set
	function deactivate() {	
		$this->updateAll(array('act' => 0),
						 array( 'Report.run_date >=' => $this->run_date,
								'Report.fund_id =' => $this->fund_id));
	}
}

?>