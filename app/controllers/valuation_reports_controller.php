<?php
class ValuationReportsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'ValuationReports';

	
	function index($fund_id = false) {
		$this->autoRender = false;
		$d = new Dispatcher();
			
		if (isset($this->params['form']['Submit']) && !$fund_id) {
			$this->Session->write('fund_chosen', $this->data['ValuationReport']['fund_id']);
			
			switch ($this->params['form']['Submit']) {
				case 'Run':
					$d->dispatch(array('controller' => 'ValuationReports', 'action' => 'run'),
								 array('data' => $this->data));
					break;
					
				default:
					$d->dispatch(array('controller' => 'ValuationReports', 'action' => 'view'),
								 array('data' => $this->data));
			}
		}
		else {
			//page just loaded, try if possible to use whatever fund was chosen on the trade blotter as the default fund choice on this page.
			if ($fund_id) {
				$fund = $fund_id;
				$this->Session->write('fund_chosen', $fund);
			}
			else if ($this->Session->check('fund_chosen')) {
				$fund = $this->Session->read('fund_chosen');
			}
			else {
				$fund = $this->ValuationReport->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
				$fund = $fund['Fund']['id'];
			}
			
			$this->data['ValuationReport'] = array('fund_id'=>$fund);
			$d->dispatch(array('controller' => 'ValuationReports', 'action' => 'view'),
								 array('data' => $this->data));
		}
	}
	
	
	function view() {																				 
		//get a list of balance calculation dates
		$fund = $this->data['ValuationReport']['fund_id'];
		App::import('model','Balance');
		$balmodel = new Balance();
		$datelist = $balmodel->find('all', array('conditions'=>array('Balance.act ='=>1, 
																	 'Balance.fund_id ='=>$fund), 
												'fields'=>array('Balance.balance_date'), 
												'order'=>array('Balance.balance_date DESC')));
		$run_dates = array();
		foreach ($datelist as $d) {
			$run_dates[$d['Balance']['balance_date']] = $d['Balance']['balance_date'];
		}
		$this->set('run_dates', $run_dates);
		
		echo debug($run_dates);
		
		//get list of reports for this fund
		$this->set('reports', $this->ValuationReport->find('all', array('limit'=>10,
																	   'conditions'=>array('ValuationReport.fund_id ='=>$fund),
																	   'order'=>array('ValuationReport.crd DESC'),
																	   'fields' => array('DISTINCT ValuationReport.final', 
																	                     'Fund.fund_name',
																						 'Fund.id',
																						 'ValuationReport.pos_date',
																						 'ValuationReport.crd'))));
		//render
		$this->dropdownchoices();
	}
	
	//run the valuation report (based on balance calculation)
	function run() {
		$this->autoRender = false;
		$fund = $this->data['ValuationReport']['fund_id'];
		$date = $this->data['ValuationReport']['run_date'];
		
		list($success, $msg) = $this->ValuationReport->getValuation($fund, $date);
		if ($success) {
			$this->redirect(array('action' => 'show', $fund, $date));
		}
		else {
			$this->Session->setFlash($msg);
			$this->redirect(array('action' => 'index', $fund));
		}
	}
	
	
	//show report
	function show($fund, $date) {
		$this->set('fundccyname', $this->ValuationReport->Fund->get_fund_ccy_name($fund));
		$this->set('valuations', $this->ValuationReport->find('all', array('conditions'=>array('ValuationReport.act'=>1, 
																							 'ValuationReport.pos_date'=>$date, 
																							 'ValuationReport.fund_id'=>$fund), 
																		 'order'=>array('ValuationReport.sec_type_id DESC', 'ValuationReport.currency_id'))));
	}
	
	
	function dropdownchoices() {
		$this->set('funds', $this->ValuationReport->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
}
?>