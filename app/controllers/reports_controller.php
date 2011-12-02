<?php
class ReportsController extends AppController {
	var $name = 'Reports';
	var $uses = array('Report','Portfolio','Fund');
	
	function index() {
		//$this->loadModel('Fund');
		$this->set('funds', $this->Fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));

		
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
					$this->Report->report_id = $this->Report->save_report();
					$this->Report->prev_report_id = $prev_report['id'];
				}
				else {
					//this run date has been done before so just retrieve the results from the portfolio table
					$this->Report->report_id = $prev_report['id'];
					$portfolio_data = $this->Report->get_prev_report_data();					
					$this->Session->write('portfolio_data', $portfolio_data); 
					$this->redirect(array('controller'=>'portfolios','action' => 'index'));
				}
			}		
		
		
		
			if ($this->Report->report_type == 'Position') {
				//Run position report which is only made up of the stock portfolio
				$portfolio_data = $this->position_report();		

					echo 'CHECKPOINT!!!!!!!!!!!!!!!!!!!!!!!</br>';
				//echo $this->Portfolio->start_date.'</br>';
				echo print_r( $portfolio_data);
				exit;
				
				$this->Session->write('portfolio_data', $portfolio_data); 
				$this->redirect(array('controller'=>'portfolios','action' => 'index'));
			}
		}
		}
		
		function position_report() {
			//ClassRegistry::init('Portfolio');	//must use this outside of a controller
			
			
			
			$this->Portfolio->report_id = $this->Report->report_id;
			$this->Portfolio->fund_id = $this->Report->fund_id;
			$this->Portfolio->port_type = 'stock';
			$this->Portfolio->end_date = $this->Report->end_date;
			
			if ($this->Portfolio->calc_start_date == null) {
				$this->Portfolio->start_date = '1999-12-31';
				$this->Portfolio->create_portfolio(1);
			}
			else {
				$this->Portfolio->start_date = $this->Report->calc_start_date;
				$this->Portfolio->prev_report_id = $this->Report->prev_report_id;
				$this->Portfolio->create_portfolio(2);
			}
			
			$this->Portfolio->save_portfolio();		
			return($this->Portfolio->portfolio);
	}
	
}

?>