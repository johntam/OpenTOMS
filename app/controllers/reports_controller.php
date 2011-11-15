<?php
class ReportsController extends AppController {
	var $name = 'Reports';
	
	function index() {
		$this->set('funds', $this->Report->Fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		if (!empty($this->data)) {
			//Run position report
			
			if ($this->data['Report']['report_type'] == 'Position') {
				$this->loadModel('Portfolio');
				$this->Portfolio->fund_id = $this->data['Report']['fund_id'];
				$this->Portfolio->get_trades('31 Dec 2010','15 Nov 2011');
				
				$this->redirect(array('controller'=>'portfolios','action' => 'index'));
			
			
			
			}
			
			
			
			
		
			//if ($this->Fund->save($this->data)) {
			//	$this->Session->setFlash('Report has been run');
			//	$this->redirect(array('action' => 'index'));
			//}
		}
	}
}

?>