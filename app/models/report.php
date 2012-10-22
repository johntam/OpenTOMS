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

class Report extends AppModel {
    var $name = 'Report';
	var $prev_report_id;
	
	function run_report() {
		App::import('model','Portfolio');
		$port = new Portfolio();
		
		$port->report_id = $this->id;
		$port->fund_id = $this->fund_id;
		$port->run_date = $this->run_date;
		$port->calc_start_date = $this->calc_start_date;
		$port->prev_report_id = $this->prev_report_id;
		$port->report_type = $this->report_type;
		
		//this will hold the final portfolio to display for this fund
		$portfolio = array();
	
		//different report types consist of different portfolios aggregated together
		switch ($this->report_type) {
			case 'Position':
				$portfolio = array_merge($portfolio, $port->get_portfolio('stock'));
				break;
			
			case 'NAV':
				$portfolio = array_merge($portfolio, $port->get_portfolio('stock'));
				$portfolio = array_merge($portfolio, $port->get_portfolio('cash'));	
				break;
		}
	
		return $portfolio;
	}
	
	//save report metadata in the reports table
	function save_report() {
		$this->create(array('Report' => array(  'act'=>1,
												'crd'=>DboSource::expression('NOW()'),
												'report_type' => $this->report_type,
												'run_date' => $this->run_date,
												'fund_id' => $this->fund_id,
												'calc_start_date' => $this->calc_start_date)));
		$this->save();
		return $this->id;
	}
	
	//find if a an active report for this report type and the specified run-date exists already
	function get_prev_report() {
		$params=array(
			'conditions' => array(  'Report.act =' => 1, 
									'Report.report_type =' => $this->report_type, 
									'Report.run_date <=' => $this->run_date,	//ignore future reports from this run date
									'Report.fund_id =' => $this->fund_id),
			'order' => array('Report.run_date DESC')
		);
		
		$latest = $this->find('first', $params);
		return(array('run_date'=>$latest['Report']['run_date'], 'id'=>$latest['Report']['id']));
	}
	
	//fetch the whole data for a report run previously
	function get_prev_report_data() {
		App::import('model','Portfolio');
		$port = new Portfolio();
		$data = $port->find('all', array('conditions'=>array('Portfolio.report_id ='=>$this->report_id)));
		
		$portfolio = array();
		switch ($this->report_type) {
			case 'Position':
				foreach ($data as $d) {
					$portfolio[] = array('0'=>array('position'=>$d['Portfolio']['position']),
												 'Sec'=>array('sec_name'=>$d['Portfolio']['sec_name'],
															  'id'=>$d['Portfolio']['sec_id']));
				}
				break;
				
			case 'NAV':
				foreach ($data as $d) {
					$portfolio[] = $d['Portfolio'];
				}
				break;
		}		
		return $portfolio;
	}
	
	//deactivate all previous reports with the given run_date
	//uses updateAll(array $fields, array $conditions)
	//NB assumes that run_date and fund_id have been set
	//
	//else if $id is passed as a parameter, then specifically deactivate that particular report
	function deactivate($id=null) {
		if (empty($id)) {
			$this->updateAll(array('act' => 0),
							 array( 'Report.run_date >=' => $this->run_date,
									'Report.fund_id =' => $this->fund_id));
		}
		else {
			$this->delete($id, false);
		}
	}
	
	//deactivate all reports run on $this->run_date for all funds and all report types
	//run this if a price or an FX rate has been updated for this run_date
	function deactivateDate() {	
		$this->updateAll(array('act' => 0),
							 array( 'Report.run_date =' => $this->run_date));
	}	
}
?>
