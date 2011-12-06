<?php
/* There are different classes of portfolio handled by this class
	port_type	description					used by
	stock		securities only				positions report, nav report
	cash		currencies only				nav report
*/
class Portfolio extends AppModel {
    var $name = 'Portfolio';
	//var $belongsTo = 'Trade';
	var $actsAs = array('Containable');
	var $calc_start_date;
	var $prev_report_id;
	var $portfolio;
	
	/* create the array containing the portfolio
		The paramter merge has the following possible values
	
		=1	The report has never been run before (or have been deactivated due to new trades/edits) for this fund so no merge with previous results is needed
		=2	This report has been run before so we need to grab those previous results and merge them in with the most recent trades since that run date. We do
			it this way to speed up report calculation as the number of trades increases for each fund.
			
	*/
	function get_portfolio($report_type) {
		$this->portfolio_type = $report_type;
		$use_prev_report=true;
		
		if ($this->calc_start_date == null) {
			$this->calc_start_date = '1999-12-31';
			$use_prev_report=false;
		}
	
		if (!$use_prev_report) {
			//No previous report found, so just get all the trades starting from the beginning
			$this->portfolio = $this->get_trades();
		}
		else {
			//First get the old results
			$prev_calc_results = $this->find('all', array('conditions' => array('Portfolio.report_id =' => $this->prev_report_id, 'Portfolio.portfolio_type =' => $this->portfolio_type), 'contain'=>false));
			
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
		
		$this->save_portfolio();
		return($this->portfolio);
	}
	
	
	//create an array of trades relevant to this portfolio
	function get_trades() {
		
		if ($this->portfolio_type == 'stock') {
			return $this->get_stock_trades();
		}
		elseif ($this->portfolio_type == 'cash') {
			return $this->get_cash_trades();
		}
	}
	
	
	//Stock Portfolio
	function get_stock_trades() {
		App::import('model','Trade');
		$trade = new Trade();
			
		$params=array(
			'conditions' => array(  'Trade.fund_id =' => $this->fund_id, 
									'Trade.trade_date >' => $this->calc_start_date, 
									'Trade.trade_date <=' => $this->run_date, 
									'Trade.cancelled <>' => 1,
									'Trade.executed =' => 1,
									'Trade.act =' => 1),
			'order' => array('Trade.trade_date DESC'),
			'contain' => false,
			'fields' => array('SUM(Trade.quantity) AS quantity','Sec.sec_name','Sec.id'),
			'group' => array('Sec.sec_name','Sec.id')
		);
		
		return($trade->find('all', $params));
	}
	
	
	//Cash Portfolio
	function get_cash_trades() {
		App::import('model','Trade');
		$trade = new Trade();
			
		$params=array(
			'conditions' => array(  'Trade.fund_id =' => $this->fund_id, 
									'Trade.trade_date >' => $this->calc_start_date, 
									'Trade.trade_date <=' => $this->run_date, 
									'Trade.cancelled <>' => 1,
									'Trade.executed =' => 1,
									'Trade.act =' => 1),
			'order' => array('Trade.trade_date DESC'),
			'contain' => false,
			'fields' => array('SUM(Trade.consideration) AS quantity','Currency.currency_iso_code','Currency.id'),
			'group' => array('Currency.currency_iso_code','Currency.id')
		);
		
		//Must convert the array to correspond to the same format as the stock portfolio, i.e. each array element must take the form
		// '0'=>array('quantity'=>) , 'Sec'=>array('sec_name'=>,'id'=>)
		$trades = $trade->find('all', $params);
		$trades_conv = array();
		foreach ($trades as $t) {
			$trades_conv[] = array('0'=>array('quantity'=>$t['0']['quantity']),
										 'Sec'=>array('sec_name'=>$t['Currency']['currency_iso_code'],
													  'id'=>$t['Currency']['id']));
		}
						
		return $trades_conv;
	}
	
	//save this portfolio to the portfolios table
	function save_portfolio() {
		foreach ($this->portfolio as $p) {
			$this->create(array('Portfolio' => array(   'crd'=>DboSource::expression('NOW()'),
														'report_id'=>$this->report_id,
														'portfolio_type'=>$this->portfolio_type,
														'run_date'=>$this->run_date,
														'fund_id'=>$this->fund_id,
														'sec_id'=>$p['Sec']['id'], 
														'sec_name'=>$p['Sec']['sec_name'], 
														'position'=>$p['0']['quantity'])));
			$this->save();
		}
	}
}

?>