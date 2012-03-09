<?php
class PositionReportsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'PositionReports';

	
	function index() {
		$this->autoRender = false;
		$d = new Dispatcher();
			
		if (isset($this->params['form']['Submit'])) {
			$this->Session->write('fund_chosen', $this->data['PositionReport']['fund_id']);
			
			switch ($this->params['form']['Submit']) {
				case 'View':
					$d->dispatch(array('controller' => 'PositionReports', 'action' => 'view'),
								 array('data' => $this->data));
					break;
				
				case 'Run':
					$d->dispatch(array('controller' => 'PositionReports', 'action' => 'run'),
								 array('data' => $this->data));
					break;
			}
		}
		else {
			//page just loaded, try if possible to use whatever fund was chosen on the trade blotter as the default fund choice on this page.
			if ($this->Session->check('fund_chosen')) {
				$fund = $this->Session->read('fund_chosen');
			}
			else {
				$fund = $this->PositionReport->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
				$fund = $fund['Fund']['id'];
			}
			
			$this->data['PositionReport'] = array('fund_id'=>$fund);
			$d->dispatch(array('controller' => 'PositionReports', 'action' => 'view'),
								 array('data' => $this->data));
		}
	}
	
	function view() {
		$this->set('reports', $this->PositionReport->find('all', array('limit'=>10, 
																	   'order'=>array('PositionReport.crd DESC'),
																	   'fields' => array('DISTINCT PositionReport.final', 
																	                     'PositionReport.fund_id', 
																						 'PositionReport.pos_date'))));
																						 
		//get a list of balance calculation dates
		$fund = $this->data['PositionReport']['fund_id'];
		App::import('model','Balance');
		$balmodel = new Balance();
		$datelist = $balmodel->find('all', array('conditions'=>array('Balance.act ='=>1, 
																	 'Balance.fund_id ='=>$fund), 
												'fields'=>array('Balance.balance_date'), 
												'order'=>array('Balance.balance_date DESC'),
												'limit'=>10));
		$run_dates = array();
		foreach ($datelist as $d) {
			$run_dates[$d['Balance']['balance_date']] = $d['Balance']['balance_date'];
		}
		$this->set('run_dates', $run_dates);
		
		//render
		$this->dropdownchoices();
		$this->render('index');
	}
	
	//run the position report (based on balance calculation)
	function run() {
		$this->autoRender = false;
		
		$fund = $this->data['PositionReport']['fund_id'];
		$date = $this->data['PositionReport']['run_date'];
		
		$this->PositionReport->getPositions($fund, $date);
		
		
	
		
	}
	
	function dropdownchoices() {
		$this->set('funds', $this->PositionReport->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
}
?>