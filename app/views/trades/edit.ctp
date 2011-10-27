<!-- File: /app/views/trades/edit.ctp -->

<table>
	<tr>
		<td colspan="4">
			<h1>Edit Trade</h1>
			<?php echo $this->Form->create('Trade', array('action' => 'edit')); ?>
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Fund</td>
		<td>Security Name</td>
		<td>Security Type</td>
		<td>Ticker</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('fund_id'); ?></td>
			<td><?php echo $this->Form->input('sec_id'); ?></td>
			<td>...</td>
			<td><?php echo $this->Form->input('ticker'); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Trade Type</td>
		<td>Quantity</td>
		<td>Order Price</td>
		<td>Trade Currency</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('trade_type_id'); ?></td>
			<td><?php echo $this->Form->input('quantity'); ?></td>
			<td><?php echo $this->Form->input('price'); ?></td>
			<td><?php echo $this->Form->input('currency'); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Decision Time</td>
		<td>Trade Date</td>
		<td>Settlement Date</td>
		<td>Trader</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('decision_time'); ?></td>
			<td><?php echo $this->Form->input('trade_date'); ?></td>
			<td><?php echo $this->Form->input('settlement_date'); ?></td>
			<td><?php echo $this->Form->input('trader_id'); ?></td>
		</tr>
		
	<tr class="highlight">
		<td>Broker</td>
		<td>Broker Contact</td>
		<td>Order Time</td>
		<td>Commission</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('broker_id'); ?></td>
			<td><?php echo $this->Form->input('broker_contact'); ?></td>
			<td><?php echo $this->Form->input('order_time'); ?></td>
			<td><?php echo $this->Form->input('commission'); ?></td>
		</tr>
	

	<tr class="highlight">
		<td>Trade Reason</td>
		<td>Executed</td>
		<td>Cancelled</td>
		<td></td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('reason_id'); ?></td>
			<td><?php echo $this->Form->input('executed'); ?></td>
			<td><?php echo $this->Form->input('cancelled'); ?></td>
			<td></td>
		</tr>
	
	<tr>
		<td colspan="4" style="text-align: center;">
			<?php
				echo $this->Form->input('oid', array('type' => 'hidden')); 
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->end('Update Trade');
			?>
		</td>
	</tr>
</table>

