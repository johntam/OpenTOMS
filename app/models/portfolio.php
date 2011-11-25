<?php
/* There are different classes of portfolio handled by this class
	port_type	description					used by
	stock		securities only				positions report, nav report
	cash		currencies only				nav report
*/
class Portfolio extends AppModel {
    var $name = 'Portfolio';
	var $belongsTo = 'Trade';
	var $actsAs = array('Containable');
	var $portfolio;
	var $port_type;
	var $fund_id;
	var $start_date;
	var $end_date;
	
	
	/* create the array containing the portfolio
		The paramter merge has the following possible values
	
		=1	The report has never been run before (or have been deactivated due to new trades/edits) for this fund so no merge with previous results is needed
		=2	This report has been run before so we need to grab those previous results and merge them in with the most recent trades since that run date. We do
			it this way to speed up report calculation as the number of trades increases for each fund.
			
	*/
	function create_portfolio($merge) {
	
		if ($merge == 1) {
			//Just get all the trades
			$this->portfolio = $this->get_trades();
		}
		elseif ($merge == 2) {
			//First get the old results
			$prev_calc_results = $this->find('all', array('conditions' => array('Portfolio.report_id =' => $this->report_id, 'Portfolio.portfolio_type =' => 'stock'), 'contain'=>false));

			
			
			//Get all the trades after the previous report end date above to the end of the current report end date
			$trades_to_add = $this->get_trades();
			
			//Now merge the two sets of data GROUP BY security id
			$port = array();
			foreach ($prev_calc_results as $p) {
				$port[$p['Portfolio']['sec_id']][] = array('position'=>$p['Portfolio']['position'],'sec_name'=>$p['Portfolio']['sec_name']);
			}
			
			foreach ($trades_to_add as $t) {
				$port[$t['Sec']['id']][] = array('position'=>$t['0']['quantity'],'sec_name'=>$t['Sec']['sec_name']);
			}
			
			
			$portfolio = array();
			foreach ($port as $key => $p1) {
				$quantity_total=0;
				$sec_name='';
				
				foreach ($p1 as $p2) {
					$quantity_total += $p2['position'];
					$sec_name=$p2['sec_name'];
				}
				$portfolio[] = array('0'=>array('quantity'=>$quantity_total),
										 'Sec'=>array('sec_name'=>$sec_name,
													  'id'=>$key));
			}
			
			$this->portfolio = $portfolio;
		}

	}
	
	
	
	//create an array of trades relevant to this portfolio
	function get_trades() {
		$params=array(
			'conditions' => array(  'Trade.fund_id =' => $this->fund_id, 
									'Trade.trade_date >' => $this->start_date, 
									'Trade.trade_date <=' => $this->end_date, 
									'Trade.cancelled <>' => 1,
									'Trade.executed =' => 1,
									'Trade.act =' => 1),
			'order' => array('Trade.trade_date DESC'),
			'contain' => false,
			'fields' => array('SUM(Trade.quantity) AS quantity','Sec.sec_name','Sec.id'),
			'group' => array('Sec.sec_name','Sec.id')
		);
		
		return($this->Trade->find('all', $params));
	}
	
	//save this portfolio to the portfolios table
	function save_portfolio() {
		foreach ($this->portfolio as $p) {
			$this->create(array('Portfolio' => array(   'crd'=>DboSource::expression('NOW()'),
														'report_id'=>$this->report_id,
														'portfolio_type'=>$this->port_type,
														'run_date'=>$this->end_date,
														'fund_id'=>$this->fund_id,
														'sec_id'=>$p['Sec']['id'], 
														'sec_name'=>$p['Sec']['sec_name'], 
														'position'=>$p['0']['quantity'])));
			$this->save();
		}
	}
}

?>