<?php
/*
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class CurrenciesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Currencies';

	function index() {
		$this->set('currencies', $this->Currency->find('all'));
	}
	
	function add() {
		$this->getSecList();
		
		if (!empty($this->data)) {
			if ($this->check_unique()) {
				if ($this->Currency->save($this->data)) {
					$this->Session->setFlash('Currency info has been saved.');
					Cache::delete('currencies');	//clear cache
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate currency in the table');
			}
		}
	}
	
	function edit($id = null) {
		$this->getSecList();
		
		if (empty($this->data)) {
			$this->data = $this->Currency->read();
		} else {
			if ($this->check_unique($id)) {
				if ($this->Currency->save($this->data)) {
					$this->Session->setFlash('Currency info has been updated.');
					Cache::delete('currencies');	//clear cache
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate currency in the table');
			}
		}
	}
	
	//Check to see that the data entered is unique for this model
	function check_unique($id = null) {		
		$currency_code = $this->data['Currency']['currency_iso_code'];
		$currency_name = $this->data['Currency']['currency_name'];
		
		$conditions=array(
			'OR' => array(	
						'Currency.currency_iso_code =' => $currency_code,
						'Currency.currency_name =' => $currency_name
					),
			'Currency.id <>' => $id,
		);
	
		$params=array(
			'conditions' => $conditions, 
		);
		
		$num = $this->Currency->find('count', $params);
		if ($num > 0) { 
			return false;
		} 
		else {
			return true;
		}
	}
	
	function getSecList() {
		//Could be a lot of securities so get the cache of this list
		if (($secsCACHE = Cache::read('secs')) === false) {
			$secsCACHE = $this->Currency->Sec->find('list', array('fields'=>array('Sec.sec_name'),'order'=>array('Sec.sec_name'),'conditions'=>array('Sec.act =' => 1)));
			Cache::write('secs', $secsCACHE);
		}

		$this->set('secs', $secsCACHE);
	}
}
?>
