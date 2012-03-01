<?php
class PositionReportsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'PositionReports';

	
	function index() {
		$this->set('reports', $this->PositionReport->find('all', array('limit'=>10, 
																		  'order'=>array('PositionReport.crd DESC'),
																		  'fields' => array(' DISTINCT PositionReport.final', 'PositionReport.fund_id', 'PositionReport.pos_date'))));
																		  
		$this->dropdownchoices();
	}
	
	
	function run() {	
		//First check that this month end is not locked
		if ($this->Balance->islocked($fund, $month, $year)) {
			$this->Session->setFlash('Cannot recalculate balances as this month end is locked.');
		}
		else {
			//work out the month end balances, the function also saves the results to the model table
			if (!$this->Balance->monthend($fund, $month, $year)) {
				$this->Session->setFlash('Problem with calculating balances.');
			}
		}
		$this->redirect(array('controller' => 'balances', 'action' => 'view/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
	}
	
	function dropdownchoices() {
		$this->set('funds', $this->PositionReport->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
	
}
?>