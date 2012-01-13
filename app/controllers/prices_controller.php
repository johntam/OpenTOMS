<?php
class PricesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Prices';

	function index() {	
		$secfilter= 0;
		$datefilter = 0;
		$to=max($this->params['pass'][0],0);
		$from=max($this->params['pass'][1],1);
		
		if ($this->params['pass'][2]) {
			$secfilter='%'.$this->data['Price']['secfilter'].'%';
			$this->set('secnamefiltered',$secfilter);
		}
		
		if ($this->params['pass'][3]) {
			$datefilter = date('Y-m-d',
						mktime(0,0,0,$this->data['Price']['datefilter']['month'],
									$this->data['Price']['datefilter']['day'],
									$this->data['Price']['datefilter']['year']));
			$this->set('datefiltered',$datefilter);
		}
		
		$this->set('fromdate',$from);
		$this->set('todate',$to);
		$this->set('prices', $this->Price->get_securities($to, $from, $secfilter, $datefilter, $this->data['Price']));
		
		//Get list of security names
		App::import('model','Sec');
		$sec = new Sec();
		$this->set('secs', $sec->find('list', array('conditions'=>array('Sec.act =' => 1), 'fields'=>array('Sec.sec_name'), 'order'=>array('Sec.sec_name'))));
	}
	
	
	
	function add() {		
		if (!empty($this->data)) {
			$this->Price->set($this->data);
			
			if ($this->Price->validates()) {
				if ($this->check_unique()) {
					if ($this->Price->save($this->data)) {
						$this->redirect(array('controller' => 'prices', 'action' => 'index',0,1,0,0));
					}
				}
				else {
					$this->Session->setFlash('Sorry, cannot enter price for duplicate date, source and security.');
				}
			}
			else {
				$this->Session->setFlash('The price field cannot be blank');
			}
		}

		$this->redirect(array('controller' => 'prices', 'action' => 'index',0,1,0,0));
	}

	
	//Check to see that the new price to be added doesn't break the unique constraint on table prices.
	function check_unique() {	
		$price_date = date('Y-m-d',
						mktime(0,0,0,$this->data['Price']['price_date']['month'],
									$this->data['Price']['price_date']['day'],
									$this->data['Price']['price_date']['year']));
		$price_source = $this->data['Price']['price_source'];
		$sec_id = $this->data['Price']['sec_id'];
		
		$conditions=array(
			'Price.price_date =' => $price_date,
			'Price.price_source =' => $price_source,
			'Price.sec_id =' => $sec_id
		);
	
		$params=array(
			'conditions' => $conditions, 
		);
		
		$num = $this->Price->find('count', $params);
		if ($num > 0) { 
			return false;
		} 
		else {
			return true;
		}
	}
	
	
	function edit($id = null) {
		if (empty($this->data)) {
			$dataset = $this->Price->get_sec_row($id);
			$this->data = $dataset['0'];
		} else {		
			if ($this->Price->save($this->data)) {
				$this->Session->setFlash('Price has been updated.');
				$this->update_report_table($this->data['Price']['price_date']);
				$this->redirect(array('action' => 'index',0,1,0,0));
			}
		}
	}
	
	
	/////////////////////
	//Pricing of FX rates
	function fxrates($datefilter=null) {	
		if (!empty($this->data['Price']['date_1'])) {
			//When Submit button has been pressed	
			$this->Price->save_fxrates($this->data['Price']);			
			$this->Session->setFlash('FX rates have been updated.');
			$this->update_report_table($this->data['Price']['fx_date']);
			$this->redirect(array('action' => '/fxrates/'.$datefilter));
		}
		elseif (!empty($this->data['Price']['datefilter'])) {
			//When Filter button has been pressed
			if (is_array($this->data['Price']['datefilter'])) {
				$datefilter = date('Y-m-d',
											mktime(0,0,0,$this->data['Price']['datefilter']['month'],
														$this->data['Price']['datefilter']['day'],
														$this->data['Price']['datefilter']['year']));
			}
			else {
				$datefilter = $this->data['Price']['datefilter'];
			}
		}
		elseif (!$datefilter) {
			$datefilter = date('Y-m-d',strtotime('-1 day'));
		}
				
		$this->set('datefilter', $datefilter);
		$this->set('prices', $this->Price->get_fxrates($datefilter));
	}
	
	//If a price has been edited, then deactivate any reports which have a run_date on this price date.
	//This is to make sure that any future run reports do not depend on these saved reports which could now be invalid.
	function update_report_table($date) {
		App::import('model','Report');
		$report = new Report();
		$report->run_date = $date;
		$report->deactivateDate();
	}
}
?>