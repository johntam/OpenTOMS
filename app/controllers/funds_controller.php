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

class FundsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Funds';

	function index() {
		$this->set('funds', $this->Fund->find('all'));
	}
	
	function add() {
		$this->set('currencies', $this->Fund->Currency->find('list', 
										array('fields'=>array(
																'Currency.currency_iso_code'),
																'order'=>array('Currency.currency_iso_code'))));
		if (!empty($this->data)) {
			if ($this->Fund->save($this->data)) {
				$this->Session->setFlash('Fund has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		$this->set('currencies', $this->Fund->Currency->find('list', 
										array('fields'=>array(
																'Currency.currency_iso_code'),
																'order'=>array('Currency.currency_iso_code'))));
		if (empty($this->data)) {
			$this->data = $this->Fund->read();
		} else {
			if ($this->Fund->save($this->data)) {
				$this->Session->setFlash('Fund has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>
