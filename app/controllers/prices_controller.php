<?php
class PricesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Prices';

	function index() {	
		if (empty($this->params['pass'][0])) {
			$to=0;
		}
		else {
			$to=max($this->params['pass'][0],0);
		}
		
		if (empty($this->params['pass'][1])) {
			$from=1;
		}
		else {
			$from=max($this->params['pass'][1],1);
		}
		
		if (!empty($this->data['Price']['secfilter'])) {
			$secfilter='%'.$this->data['Price']['secfilter'].'%';
			$this->set('secnamefiltered',$secfilter);
		}
		
		if (!empty($this->data['Price']['datefilter'])) {
			$datefilter = date('Y-m-d',
						mktime(0,0,0,$this->data['Price']['datefilter']['month'],
									$this->data['Price']['datefilter']['day'],
									$this->data['Price']['datefilter']['year']));
			$this->set('datefiltered',$datefilter);
		}
		
		
	
		if (isset($datefilter)) {
			$conditions=array(
				'Price.price_date =' => $datefilter
			);
		}
		elseif (isset($secfilter)) {
			$conditions=array(
				'Sec.sec_name LIKE' => $secfilter
			);
		}
		else {
			$conditions=array(
				'Price.price_date >=' => date('Y-m-d',strtotime('-'.$from.' weeks')),
				'Price.price_date <=' => date('Y-m-d',strtotime('-'.$to.' weeks'))
			);
		}
		
		$params=array(
			'fields' => array('Price.price_date', 'Price.price_source', 'Sec.sec_name', 'Price.price', 'Price.id', 'SecType.sec_type'),
			'joins' => array(
							array('table'=>'secs',
								  'alias'=>'Sec',
								  'type'=>'inner',
								  'foreignKey'=>false,
								  'conditions'=>
										array('Price.sec_id=Sec.id ')
								  ),
							array('table'=>'sec_types',
								  'alias'=>'SecType',
								  'type'=>'inner',
								  'foreignKey'=>false,
								  'conditions'=>
										array('SecType.id=Sec.sec_type_id ')
								  )
							),
			'conditions' => $conditions, //array of conditions
			'order' => array('Price.price_date DESC') //string or array defining order
		);	
		
		$this->set('fromdate',$from);
		$this->set('todate',$to);
		$this->set('prices', $this->Price->find('all', $params));
		
		//Get list of security names
		App::import('model','Sec');
		$sec = new Sec();
		$this->set('secs', $sec->find('list', array('fields'=>array('Sec.sec_name'), 'order'=>array('Sec.sec_name'))));
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
					$this->Session->setFlash('Sorry, cannot enter price for duplicate date, source and security.');
				}
			}
			else {
				$this->Session->setFlash('The price field cannot be blank');
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
		
		if (empty($this->data)) {
			$this->data = $this->Price->read();
		} else {
			if ($this->Price->save($this->data)) {
				$this->Session->setFlash('Price has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

}
?>