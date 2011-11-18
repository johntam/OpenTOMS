<?php
class ReportsController extends AppController {
	var $name = 'Reports';
	
	function index() {
		$this->set('funds', $this->Report->Fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		if (!empty($this->data)) {
			//Run position report			
			$report_type = $this->data['Report']['report_type'];
			$this->Report->fund_id = $this->data['Report']['fund_id'];
			$this->Report->start_date = date('Y-m-d',mktime(0,0,0,12,31,1999));
			$this->Report->end_date = date('Y-m-d',mktime(0,0,0,$this->data['Report']['run_date']['month'],$this->data['Report']['run_date']['day'],$this->data['Report']['run_date']['year']));
			
			if ($report_type == 'Position') {
				$trades = $this->Report->position_report();
				
				//echo print_r($trades);
				//exit;
				
				
				$this->Session->write('trades', $trades); 
				$this->redirect(array('controller'=>'portfolios','action' => 'index'));
			}
		}
	}
}

?>