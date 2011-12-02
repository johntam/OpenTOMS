<?php
class PortfoliosController extends AppController {
	var $name = 'Portfolios';
	
	
	function index() {
		//$trades = array(); 
		//	foreach($this->params as $t) {
		//		if (isset($t['Trade']) && is_array($t['Trade'])) {
		//			array_push($trades, $t);
		//			}
		//	}
		$portfolio_data = $this->Session->read('portfolio_data');	
		$this->set('portfolio_data', $portfolio_data);
	}
	
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
			$prev_calc_results = $this->find('all', array('conditions' => array('Portfolio.report_id =' => $this->prev_report_id, 'Portfolio.portfolio_type =' => 'stock'), 'contain'=>false));
			
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
			
			//Get the return array in the right format for index.ctp in the portfolios view folder.
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
}

?>