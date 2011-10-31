<!-- File: /app/views/secs/view.ctp -->

<table>	
	<tr>
		<td>
			<h1>View Security</h1>
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
			<td><?php echo $sec['Sec']['country']; ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Underlying Sec Id</td>
		<td>Tradar Id</td>
		<td>SEDOL</td>
		<td>Beta</td>
		<td>Delta</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $sec['Sec']['underlying_secid']; ?></td>
			<td><?php echo $sec['Sec']['tradarid']; ?></td>
			<td><?php echo $sec['Sec']['sedol']; ?></td>
			<td><?php echo $sec['Sec']['beta']; ?></td>
			<td><?php echo $sec['Sec']['delta']; ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Prev Coupon Date</td>
		<td>Valpoint</td>
		<td>ISIN</td>
		<td>Industry</td>
		<td>First Settles Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $sec['Sec']['prev_coupon_date']; ?></td>
			<td><?php echo $sec['Sec']['valpoint']; ?></td>
			<td><?php echo $sec['Sec']['isin_code']; ?></td>
			<td><?php echo $sec['Sec']['industry']; ?></td>
			<td><?php echo $sec['Sec']['first_settles_date']; ?></td>
		</tr>
		
	<tr class="highlight">
		<td>First Accrual Date</td>
		<td>Exchange</td>
		<td>First Coupon Date</td>
		<td>Dividend Amount</td>
		<td>Ex Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $sec['Sec']['first_accrual_date']; ?></td>
			<td><?php echo $sec['Sec']['exchange']; ?></td>
			<td><?php echo $sec['Sec']['first_coupon_date']; ?></td>
			<td><?php echo $sec['Sec']['dividend_amount']; ?></td>
			<td><?php echo $sec['Sec']['ex_date']; ?></td>
		</tr>
	

	<tr class="highlight">
		<td>Dividend Date</td>
		<td>CUSIP</td>
		<td>Coupon Pay</td>
		<td>Coupon</td>
		<td>RIC</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $sec['Sec']['dividend_date']; ?></td>
			<td><?php echo $sec['Sec']['cusip_code']; ?></td>
			<td><?php echo $sec['Sec']['coupon_pay']; ?></td>
			<td><?php echo $sec['Sec']['coupon']; ?></td>
			<td><?php echo $sec['Sec']['ric_code']; ?></td>
			<td></td>
		</tr>
	
	<tr>
	
	<tr class="highlight">
		<td>Price</td>
		<td>Maturity</td>
		<td>Currency</td>
		<td>Expiry Date</td>
		<td>Price Source</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $sec['Sec']['price']; ?></td>
			<td><?php echo $sec['Sec']['maturity']; ?></td>
			<td><?php echo $sec['Sec']['currency']; ?></td>
			<td><?php echo $sec['Sec']['expiry_date']; ?></td>
			<td><?php echo $sec['Sec']['price_source']; ?></td>
		</tr>
	<tr class="highlight">
		<td>Amount Shares Out</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

		<tr class="altrow">
			<td><?php echo $sec['Sec']['amount_shares_out']; ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	
</table>
