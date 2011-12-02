<?php

class Report extends AppModel {
    var $name = 'Report';
	//var $belongsTo = 'Portfolio, Fund';
	var $prev_report_id;
	
	
	
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
		ClassRegistry::init('Portfolio');	//must use this outside of a controller
		$data = $this->find('all', array('conditions'=>array('Portfolio.report_id ='=>$this->report_id)));
		
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
		
		//echo 'in deactivate()</br>';
		//echo 'run_date='.$this->run_date;
		//echo 'fund_id='.$this->fund_id;
		//exit;
	
		$this->updateAll(array('act' => 0),
						 array( 'Report.run_date >=' => $this->run_date,
								'Report.fund_id =' => $this->fund_id));
	}
}

?>