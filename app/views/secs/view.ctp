<!-- File: /app/views/secs/view.ctp -->

<table>	
	<tr>
		<td>
			<h1>View Security</h1>
			<div class="high">
				<?php echo $this->Html->link('Add Security', array('controller' => 'secs', 'action' => 'add')); ?>
			</div>
		</td>
	</tr>
	
	<tr>
		<td>
			<?php echo $this->Html->link('Edit This Security', array('controller' => 'secs', 'action' => 'edit', $sec['Sec']['id'])); ?>
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
			<td><?php echo $sec['Sec']['id']; ?></td>
			<td><?php echo $sec['SecType']['sec_type_name']; ?></td>
			<td><?php echo $sec['Sec']['sec_name']; ?></td>
			<td><?php echo $sec['Sec']['ticker']; ?></td>
			<td><?php echo $sec['Country']['country_name']; ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Underlying Sec Id</td>
		<td>RIC</td>
		<td>SEDOL</td>
		<td>Beta</td>
		<td>Delta</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $sec['Sec']['underlying_secid']; ?></td>
			<td><?php echo $sec['Sec']['ric_code']; ?></td>
			<td><?php echo $sec['Sec']['sedol']; ?></td>
			<td><?php echo $sec['Sec']['beta']; ?></td>
			<td><?php echo $sec['Sec']['delta']; ?></td>
		</tr>
	
	<tr class="highlight">
		<td>CUSIP</td>
		<td>Valpoint</td>
		<td>ISIN</td>
		<td>Industry</td>
		<td>First Settles Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $sec['Sec']['cusip_code']; ?></td>
			<td><?php echo $sec['Sec']['valpoint']; ?></td>
			<td><?php echo $sec['Sec']['isin_code']; ?></td>
			<td><?php echo $sec['Industry']['industry_name']; ?></td>
			<td><?php echo $sec['Sec']['first_settles_date']; ?></td>
		</tr>
		
	<tr class="highlight">
		<td>First Accrual Date</td>
		<td>Exchange</td>
		<td>Dividend Date</td>
		<td>Dividend Amount</td>
		<td>Ex Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $sec['Sec']['first_accrual_date']; ?></td>
			<td><?php echo $sec['Exchange']['exchange_name']; ?></td>
			<td><?php echo $sec['Sec']['dividend_date']; ?></td>
			<td><?php echo $sec['Sec']['dividend_amount']; ?></td>
			<td><?php echo $sec['Sec']['ex_date']; ?></td>
		</tr>
	

	<tr class="highlight">
		<td>Prev Coupon Date</td>
		<td>Maturity</td>
		<td>Coupon Frequency</td>
		<td>Coupon(%)</td>
		<td>Calculation Type</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $sec['Sec']['prev_coupon_date']; ?></td>
			<td></td>
			<td><?php echo $sec['Sec']['coupon_frequency']; ?></td>
			<td><?php echo $sec['Sec']['coupon']; ?></td>
			<td><?php echo $sec['Sec']['calc_type']; ?></td>
		</tr>
	
	<tr>
	
	<tr class="highlight">
		<td></td>
		<td><?php echo $sec['Sec']['maturity']; ?></td>
		<td>Currency</td>
		<td></td>
		<td>Price Source</td>
	</tr>

		<tr class="altrow">
			<td></td>
			<td></td>
			<td><?php echo $sec['Currency']['currency_iso_code']; ?></td>
			<td></td>
			<td><?php echo $sec['Sec']['price_source']; ?></td>
		</tr>
	<tr class="highlight">
		<td>Amount Shares Out</td>
		<td>Active</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

		<tr class="altrow">
			<td><?php echo $sec['Sec']['amount_shares_out']; ?></td>
			<td><?php if ($sec['Sec']['act']==0) {echo 'No';} else {echo 'Yes';}; ?></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	
</table>
