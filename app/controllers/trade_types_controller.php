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

class TradeTypesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'TradeTypes';

	function index() {		
		$this->set('tradetypes', $this->TradeType->find('all', array('fields' => array('TradeType.id','TradeType.trade_type','TradeType.category','Debit.account_name','Credit.account_name'))));
	}
	
	function add() {
		$this->set('accountlist', $this->TradeType->Debit->find('list', array('fields'=>array('Debit.account_name'), 'order'=>'Debit.account_name')));
		
		if (!empty($this->data)) {
			if ($this->TradeType->save($this->data)) {
				$this->Session->setFlash('Trade type has been saved.');
				Cache::delete('trade_type_credit');	//clear cache used in trades page
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		$this->set('accountlist', $this->TradeType->Debit->find('list', array('fields'=>array('Debit.account_name'), 'order'=>'Debit.account_name')));
		
		if (empty($this->data)) {
			$this->data = $this->TradeType->read();
		} 
		else {	
			if ($this->TradeType->save($this->data)) {
				$this->Session->setFlash('Trade Type info has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>
