<!--
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
-->	
	
<table>
	<tr>
		<td colspan="5">
			<h1>Add Security</h1>
			<?php echo $this->Form->create('Sec'); ?>
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Security Id</td>
		<td>Security Type</td>
		<td>Security Name</td>
		<td>Ticker</td>
		<td>Country</td>
	</tr>
	
		<tr class="altrow">
			<td></td>
			<td><?php echo $this->Form->input('sec_type_id', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sec_name', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('ticker', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('country_id', array('label'=>false)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Underlying Sec Id</td>
		<td>RIC</td>
		<td>SEDOL</td>
		<td>Beta</td>
		<td>Delta</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('underlying_secid', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('ric_code', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sedol', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('beta', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('delta', array('label'=>false)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>CUSIP</td>
		<td>Valpoint</td>
		<td>ISIN</td>
		<td>Industry</td>
		<td>First Settles Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('cusip_code', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('valpoint', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('isin_code', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('industry_id', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('first_settles_date', array('label'=>false,'empty'=>' ')); ?></td>
		</tr>
		
	<tr class="highlight">
		<td>First Accrual Date</td>
		<td>Exchange</td>
		<td>Dividend Date</td>
		<td>Dividend Amount</td>
		<td>Ex Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('first_accrual_date', array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('exchange_id', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('dividend_date', array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('dividend_amount', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('ex_date', array('label'=>false,'empty'=>' ')); ?></td>
		</tr>
	

	<tr class="highlight">
		<td>Prev Coupon Date</td>
		<td>Maturity</td>
		<td>Coupon Frequency</td>
		<td>Coupon (%)</td>
		<td>Calculation Type</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('prev_coupon_date', array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('maturity', array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('coupon_frequency', array('label'=>false, 'options' => array(''=>null,'semi'=>'semi','quart'=>'quart','ann'=>'ann'))); ?></td>
			<td><?php echo $this->Form->input('coupon', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('calc_type', array('label'=>false, 'options' => array(''=>null,'30/360'=>'30/360','30/365'=>'30/365'))); ?></td>
		</tr>
	
	<tr>
	
	<tr class="highlight">
		<td>Strike</td>
		<td></td>
		<td>Currency</td>
		<td></td>
		<td>Price Source</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('strike', array('label'=>false)); ?></td>
			<td></td>
			<td><?php echo $this->Form->input('currency_id', array('label'=>false)); ?></td>
			<td></td>
			<td><?php echo $this->Form->input('price_source', array('label'=>false)); ?></td>
		</tr>
	<tr class="highlight">
		<td>Amount Shares Out</td>
		<td>Active</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('amount_shares_out', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('act',array('options' => array(0=>'No',1=>'Yes'), 'label'=>false, 'default'=>1)); ?></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	
	<tr>
		<td colspan="5" style="text-align: center;">
			<?php
				echo $this->Form->end('Save Security');
			?>
		</td>
	</tr>
</table>
