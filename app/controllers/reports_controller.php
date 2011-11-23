<?php
class ReportsController extends AppController {
	var $name = 'Reports';
	
	function index() {
		$this->set('funds', $this->Report->Fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		if (!empty($this->data)) {
			$this->Report->report_type = $this->data['Report']['report_type'];
			$this->Report->fund_id = $this->data['Report']['fund_id'];
			$this->Report->end_date = date('Y-m-d',mktime(0,0,0,$this->data['Report']['run_date']['month'],$this->data['Report']['run_date']['day'],$this->data['Report']['run_date']['year']));
			
			$last_run_date = $this->Report->get_prev_run_date();
			if (!isset($last_run_date)) {
				$last_run_date = '1999-12-31';
			}
			$this->Report->calc_start_date = $last_run_date;
			
			if ($last_run_date == $this->Report->end_date) {
				echo 'Prev date exists!';
				exit;
			}
			else {
				$this->Report->report_id = $this->Report->save_report();
			
				if ($this->Report->report_type == 'Position') {
					//Run position report which is only made up of the stock portfolio
					$portfolio_data = $this->Report->position_report();
					$this->Session->write('portfolio_data', $portfolio_data); 
					$this->redirect(array('controller'=>'portfolios','action' => 'index'));
				}
			}
			
			
			
			
			
		}
	}
}

?>