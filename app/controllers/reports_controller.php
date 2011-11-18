<?php
class ReportsController extends AppController {
	var $name = 'Reports';
	
	function index() {
		$this->set('funds', $this->Report->Fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		if (!empty($this->data)) {
			//Run position report			
			$report_type = $this->data['Report']['report_type'];
			$fund_id = $this->data['Report']['fund_id'];
			$start_date = date('Y-m-d',mktime(0,0,0,12,31,1999));
			$end_date = date('Y-m-d',mktime(0,0,0,$this->data['Report']['run_date']['month'],$this->data['Report']['run_date']['day'],$this->data['Report']['run_date']['year']));
			
			if ($report_type == 'Position') {
				$this->loadModel('Portfolio');
				$this->Portfolio->fund_id = $fund_id;			
				$trades = $this->Portfolio->get_trades($fund_id,$start_date,$end_date);
				
				//echo print_r($trades);
				//exit;
				
				
				$this->Session->write('trades', $trades); 
				$this->redirect(array('controller'=>'portfolios','action' => 'index'));
			}
		}
	}
}

?>