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
			<td><?php echo $this->Form->input('sec_type_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sec_name',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('ticker',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('country',array('label'=>false)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Underlying Sec Id</td>
		<td>Tradar Id</td>
		<td>SEDOL</td>
		<td>Beta</td>
		<td>Delta</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('underlying_secid',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('tradarid',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sedol',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('beta',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('delta',array('label'=>false)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Prev Coupon Date</td>
		<td>Valpoint</td>
		<td>ISIN</td>
		<td>Industry</td>
		<td>First Settles Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('prev_coupon_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('valpoint',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('isin_code',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('industry',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('first_settles_date',array('label'=>false)); ?></td>
		</tr>
		
	<tr class="highlight">
		<td>First Accrual Date</td>
		<td>Exchange</td>
		<td>First Coupon Date</td>
		<td>Dividend Amount</td>
		<td>Ex Date</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('first_accrual_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('exchange',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('first_coupon_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('dividend_amount',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('ex_date',array('label'=>false)); ?></td>
		</tr>
	

	<tr class="highlight">
		<td>Dividend Date</td>
		<td>CUSIP</td>
		<td>Coupon Pay</td>
		<td>Coupon</td>
		<td>RIC</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('dividend_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('cusip_code',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('coupon_pay',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('coupon',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('ric_code',array('label'=>false)); ?></td>
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
			<td><?php echo $this->Form->input('price',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('maturity',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('expiry_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('price_source',array('label'=>false)); ?></td>
		</tr>
	<tr class="highlight">
		<td>Amount Shares Out</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('amount_shares_out',array('label'=>false)); ?></td>
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

