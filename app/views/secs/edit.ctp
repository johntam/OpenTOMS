<!-- File: /app/views/secs/edit.ctp -->

<table>
	<tr>
		<td colspan="5">
			<h1>Edit Security</h1>
			<?php echo $this->Form->create('Sec', array('action' => 'edit')); ?>
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
			<td><?php echo $this->data['Sec']['id']; ?></td>
			<td><?php echo $this->Form->input('sec_type_id'); ?></td>
			<td><?php echo $this->Form->input('sec_name'); ?></td>
			<td><?php echo $this->Form->input('ticker'); ?></td>
			<td><?php echo $this->Form->input('country'); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Underlying Sec Id</td>
		<td>Tradar Id</td>
		<td>SEDOL</td>
		<td>Beta</td>
		<td>Delta</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('underlying_secid'); ?></td>
			<td><?php echo $this->Form->input('tradarid'); ?></td>
			<td><?php echo $this->Form->input('sedol'); ?></td>
			<td><?php echo $this->Form->input('beta'); ?></td>
			<td><?php echo $this->Form->input('delta'); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Prev Coupon Date</td>
		<td>Valpoint</td>
		<td>ISIN</td>
		<td>Industry</td>
		<td>First Settles Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('prev_coupon_date'); ?></td>
			<td><?php echo $this->Form->input('valpoint'); ?></td>
			<td><?php echo $this->Form->input('isin_code'); ?></td>
			<td><?php echo $this->Form->input('industry'); ?></td>
			<td><?php echo $this->Form->input('first_settles_date'); ?></td>
		</tr>
		
	<tr class="highlight">
		<td>First Accrual Date</td>
		<td>Exchange</td>
		<td>First Coupon Date</td>
		<td>Dividend Amount</td>
		<td>Ex Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('first_accrual_date'); ?></td>
			<td><?php echo $this->Form->input('exchange'); ?></td>
			<td><?php echo $this->Form->input('first_coupon_date'); ?></td>
			<td><?php echo $this->Form->input('dividend_amount'); ?></td>
			<td><?php echo $this->Form->input('ex_date'); ?></td>
		</tr>
	

	<tr class="highlight">
		<td>Dividend Date</td>
		<td>CUSIP</td>
		<td>Coupon Pay</td>
		<td>Coupon</td>
		<td>RIC</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('dividend_date'); ?></td>
			<td><?php echo $this->Form->input('cusip_code'); ?></td>
			<td><?php echo $this->Form->input('coupon_pay'); ?></td>
			<td><?php echo $this->Form->input('coupon'); ?></td>
			<td><?php echo $this->Form->input('ric_code'); ?></td>
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
			<td><?php echo $this->Form->input('price'); ?></td>
			<td><?php echo $this->Form->input('maturity'); ?></td>
			<td><?php echo $this->Form->input('currency'); ?></td>
			<td><?php echo $this->Form->input('expiry_date'); ?></td>
			<td><?php echo $this->Form->input('price_source'); ?></td>
		</tr>
	<tr class="highlight">
		<td>Amount Shares Out</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('amount_shares_out'); ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	
	<tr>
		<td colspan="5" style="text-align: center;">
			<?php
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->end('Update Security');
			?>
		</td>
	</tr>
</table>

