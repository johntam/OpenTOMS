<?php
class PricesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Prices';

	function index() {
		if (empty($this->params['pass'][0])) {
			$to=0;
		}
		else {
			$to=$this->params['pass'][0];
		}
		
		if (empty($this->params['pass'][1])) {
			$from=1;
		}
		else {
			$from=$this->params['pass'][1];
		}
		
		if (empty($this->data['Price']['secfilter'])) {
			$secfilter='%';
		}
		else {
			$secfilter='%'.$this->data['Price']['secfilter'].'%';
		}
		
		$conditions=array(
			'Sec.sec_name LIKE' => $secfilter,
			'Price.price_date >=' => date('Y-m-d',strtotime('-'.$from.' weeks')),
			'Price.price_date <=' => date('Y-m-d',strtotime('-'.$to.' weeks'))
		);
	
		$params=array(
			'conditions' => $conditions, //array of conditions
			'order' => array('Price.crd DESC') //string or array defining order
		);
		
		//echo print_r($params);
		//exit;
		
		$this->set('prices', $this->Price->find('all', $params));
		$this->set('secs', $this->Price->Sec->find('list', array('fields'=>array('Sec.sec_name'))));
	}
	
	function add() {		
		
		if (!empty($this->data)) {
			$this->Price->set($this->data);
			
			if ($this->Price->validates()) {
				if ($this->check_unique()) {
					if ($this->Price->save($this->data)) {
						$this->redirect(array('controller' => 'prices', 'action' => 'index',0,1));
					}
				}
				else {
					$this->Session->setFlash('Sorry, cannot enter duplicate price for given date, source and security.');
				}
			}
			else {
				$this->Session->setFlash('Please leave no field blank whilst adding a new price');
			}
		}

		$this->redirect(array('controller' => 'prices', 'action' => 'index',0,1));
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
		$this->Sec->id = $id;
		$this->set('secTypes', $this->Sec->SecType->find('list', array('fields'=>array('SecType.sec_type_name'))));
		
		if (empty($this->data)) {
			$this->data = $this->Sec->read();
		} else {
			if ($this->Sec->save($this->data)) {
				$this->Session->setFlash('Security has been updated.');
				$this->redirect(array('action' => 'view',$id));
			}
		}
	}

}
?>