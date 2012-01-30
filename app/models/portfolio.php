<?php
/* There are different classes of portfolio handled by this class
	port_type	description					used by
	stock		securities only				positions report, nav report
	cash		currencies only				nav report
*/
class Portfolio extends AppModel {
    var $name = 'Portfolio';
	var $actsAs = array('Containable');
	var $calc_start_date;
	var $prev_report_id;
	var $portfolio;
	var $report_type;
	var $savedata;	//determines whether we may save the current portfolio or not
	
	/* create the array containing the portfolio
		The paramter merge has the following possible values
	
		=1	The report has never been run before (or have been deactivated due to new trades/edits) for this fund so no merge with previous results is needed
		=2	This report has been run before so we need to grab those previous results and merge them in with the most recent trades since that run date. We do
			it this way to speed up report calculation as the number of trades increases for each fund.
	*/
	function get_portfolio($port_type) {
		$this->portfolio_type = $port_type;
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
				$port[$t['Sec']['id']][] = array('position'=>$t['0']['position'],'sec_name'=>$t['Sec']['sec_name']);
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
				$portfolio[] = array('0'=>array('position'=>$quantity_total),
									 'Sec'=>array('sec_name'=>$sec_name,
												  'id'=>$key));
			}
			
			$this->portfolio = $portfolio;	
		}
		
		//In the case of the NAV report, calculate some extra columns
		if ($this->report_type == 'NAV') {
			$this->calc_market_values();
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
			'fields' => array('SUM(Trade.quantity) AS position','Sec.sec_name','Sec.id'),
			'group' => array('Sec.sec_name','Sec.id HAVING position <> 0')
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
			'fields' => array('SUM(Trade.consideration + Trade.notional_value) AS position','Currency.currency_iso_code','Currency.sec_id'),
			'group' => array('Currency.currency_iso_code','Currency.sec_id HAVING position <> 0')
		);
		
		
		/*
		//First get the cash consideration of ordinary non-derivative trades
		$params=array(
			'fields' => array('SUM(Trade.consideration) AS position','Currency.currency_iso_code','Currency.sec_id'),
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
		*/
		
		//Must convert the array to correspond to the same format as the stock portfolio, i.e. each array element must take the form
		// '0'=>array('position'=>) , 'Sec'=>array('sec_name'=>,'id'=>)
		$trades = $trade->find('all', $params);
		$trades_conv = array();
		foreach ($trades as $t) {
			$trades_conv[] = array('0'=>array('position'=>$t['0']['position']),
								   'Sec'=>array('sec_name'=>$t['Currency']['currency_iso_code'],
											    'id'=>$t['Currency']['sec_id']));
		}
											
		return $trades_conv;
	}
	
	//Only save data to portfolios table if the $savedata variable is set to true
	function save_portfolio() {	
		if ($this->savedata) {
			switch ($this->report_type) {
				case 'Position':
					$this->save_portfolio_simple();
					break;
					
				case 'NAV':
					$this->save_portfolio_extra();
					break;
					
			}
		}
		else {
			//delete the report record as well
			App::import('model','Report');
			$report = new Report();
			$report->deactivate($this->report_id);
		}
	}
	
	
	//save this portfolio to the portfolios table
	function save_portfolio_simple() {
		foreach ($this->portfolio as $p) {					
			$this->create(array('Portfolio' => array(   'crd'=>DboSource::expression('NOW()'),
														'report_id'=>$this->report_id,
														'portfolio_type'=>$this->portfolio_type,
														'run_date'=>$this->run_date,
														'fund_id'=>$this->fund_id,
														'sec_id'=>$p['Sec']['id'], 
														'sec_name'=>$p['Sec']['sec_name'], 
														'position'=>$p['0']['position'])));
			$this->save();
		}
	}
	
	
	//save this portfolio to the portfolios table
	//contains the extra columns for the NAV report
	function save_portfolio_extra() {
		foreach ($this->portfolio as $p) {					
			$this->create(array('Portfolio' => array(   'crd'=>DboSource::expression('NOW()'),
														'report_id'=>$this->report_id,
														'portfolio_type'=>$this->portfolio_type,
														'run_date'=>$this->run_date,
														'fund_id'=>$this->fund_id,
														'sec_id'=>$p['id'], 
														'sec_name'=>$p['sec_name'], 
														'position'=>$p['position'],
														'currency'=>$p['currency'],
														'price'=>$p['price'],
														'mkt_val_local'=>$p['mkt_val_local'],
														'mkt_val_fund'=>$p['mkt_val_fund'])));
			$this->save();
		}
	}
	
	
	//Calculate market values
	function calc_market_values() {
		//First fetch all the prices at the run date for the complete list of securities
		App::import('model','Sec');
		$sec = new Sec();
			
		$params=array(
			'fields' => array('Sec.id', 'Sec.sec_type_id', 'Sec.valpoint', 'Sec.currency_id', 'Price.price'),
			'joins' => array(
							array('table'=>'prices',
								  'alias'=>'Price',
								  'type'=>'left',
								  'foreignKey'=>false,
								  'conditions'=>
										array('Price.sec_id=Sec.id',
											  "Price.price_date = '$this->run_date'")
								  )
							)
		);
		$dataset = $sec->find('all', $params);
		
		//flatten $sec_price
		$sec_price	= array();
		foreach ($dataset as $d) {
			$sec_price[$d['Sec']['id']]['sec_type_id'] = $d['Sec']['sec_type_id'];
			$sec_price[$d['Sec']['id']]['valpoint'] = $d['Sec']['valpoint'];
			$sec_price[$d['Sec']['id']]['price'] = $d['Price']['price'];
			$sec_price[$d['Sec']['id']]['currency_id'] = $d['Sec']['currency_id'];
		}
		
		//Get fund currency
		App::import('model','Fund');
		$fund = new Fund();
		$fund_ccyid = $fund->get_fund_ccy($this->fund_id);
		
		//Get fx rates for this date.
		$fxrates = $this->get_fx();
		
		//Set the class variable $savedata to true to start with. If the nav calculation cannot be completed due to
		//missing prices or fx rates, then set it to false.
		$this->savedata = true;
		
		$nav = array();
		foreach ($this->portfolio as $p) {
			$id = $p['Sec']['id'];
			$name = $p['Sec']['sec_name'];
			$qty = $p['0']['position'];
			$price = $sec_price[$id]['price'];
			$valp = $sec_price[$id]['valpoint'];
			$ccyid = $sec_price[$id]['currency_id'];
			$fx_to_base = $fxrates[$ccyid]['fx_rate'] / $fxrates[$fund_ccyid]['fx_rate'];
			$msg = null;
			
			if (empty($price)) {
				$price=0;
				$msg=$msg.'Price missing. ';
				$this->savedata = false;
			}
			
			if (empty($fx_to_base)) {
				$fx_to_base = 0;
				$msg=$msg.'FX rate missing. ';
				$this->savedata = false;
			}
			
			$nav[] = array( 'id'=>$id, 
							'sec_name'=>$name,
							'position'=>$qty,
							'currency'=>$fxrates[$ccyid]['ccy'],
							'price'=>$price,
							'mkt_val_local'=>$qty*$price*$valp,
							'mkt_val_fund'=>$qty*$price*$valp*$fx_to_base,
							'message'=>$msg
							);
		}
				
		$this->portfolio = $nav;
		return($this->portfolio);
	}
	
	
	//get the fx_rates (base currency = fund currency)
	function get_fx() {
		App::import('model','Price');
		$price = new Price();
		$data = $price->get_fxrates($this->run_date);
		
		$flat = array();
		foreach ($data as $d) {
			$flat[$d['Currency']['id']]['ccy'] = $d['Sec']['sec_name'];
			$flat[$d['Currency']['id']]['fx_rate'] = $d['Price']['fx_rate'];
		}
		
		return($flat);
	}
}