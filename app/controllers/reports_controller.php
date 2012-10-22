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

class ReportsController extends AppController {
	var $name = 'Reports';
	
	function index() {
		//Get list of fund names.
		App::import('model','Fund');
		$fund = new Fund();
		$this->set('funds', $fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		//Start processing the data returned back in the form.
		if (!empty($this->data)) {
			$this->Report->report_type = $this->data['Report']['report_type'];
			$this->Report->fund_id = $this->data['Report']['fund_id'];
			$this->Report->run_date = date('Y-m-d',mktime(0,0,0,$this->data['Report']['run_date']['month'],$this->data['Report']['run_date']['day'],$this->data['Report']['run_date']['year']));
	
			//Get the previous date that this particular report was run
			$prev_report = $this->Report->get_prev_report();
			
			if (!isset($prev_report['run_date'])) {
				//No previous run for this date, so start trade capture from the beginning.
				$this->Report->calc_start_date = null;
				$this->Report->id = $this->Report->save_report();
			}
			else {
				if ($prev_report['run_date'] != $this->Report->run_date) {
					//today's date has not been run yet so we need to run it
					$this->Report->calc_start_date = $prev_report['run_date'];
					$this->Report->id = $this->Report->save_report();
					$this->Report->prev_report_id = $prev_report['id'];					
				}
				else {
					//this run date has been done before so just retrieve the results from the portfolio table
					$this->Report->report_id = $prev_report['id'];
					$portfolio_data = $this->Report->get_prev_report_data();
					$this->Session->write('portfolio_data', $portfolio_data); 
					$this->redirect(array('controller'=>'portfolios','action' => 'index', $this->Report->report_type));
				}
			}		
		
			$portfolio_data = $this->Report->run_report();
			$this->Session->write('portfolio_data', $portfolio_data); 
			$this->redirect(array('controller'=>'portfolios','action' => 'index', $this->Report->report_type));
		}
	}
		
		
	
}
?>
