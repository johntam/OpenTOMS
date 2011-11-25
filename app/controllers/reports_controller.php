<?php
class ReportsController extends AppController {
	var $name = 'Reports';
	
	function index() {
		$this->set('funds', $this->Report->Fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		if (!empty($this->data)) {
			$this->Report->report_type = $this->data['Report']['report_type'];
			$this->Report->fund_id = $this->data['Report']['fund_id'];
			$this->Report->end_date = date('Y-m-d',mktime(0,0,0,$this->data['Report']['run_date']['month'],$this->data['Report']['run_date']['day'],$this->data['Report']['run_date']['year']));
	
			//Get the previous date that this particular report was run
			$prev_report = $this->Report->get_prev_report();
			
			if (!isset($prev_report['run_date'])) {
				$this->Report->calc_start_date = null;
				$this->Report->report_id = $this->Report->save_report();
			}
			else {
				if ($prev_report['run_date'] != $this->Report->end_date) {
					//today's date has not been run yet so we need to run it
					$this->Report->calc_start_date = $prev_report['run_date'];
					$this->Report->save_report();
					$this->Report->report_id = $prev_report['id'];
				}
				else {
					//this run date has been done before so just retrieve the results from the portfolio table
					
				}
			}			
		
			if ($this->Report->report_type == 'Position') {
				//Run position report which is only made up of the stock portfolio
				$portfolio_data = $this->Report->position_report();				
				$this->Session->write('portfolio_data', $portfolio_data); 
				$this->redirect(array('controller'=>'portfolios','action' => 'index'));
			}
	
			
		}
	}
}

?>