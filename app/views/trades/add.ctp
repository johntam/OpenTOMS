<!-- File: /app/views/trades/add.ctp -->	

<?php echo $this->Form->create('Trade'); ?>

<table>	
	<tr><td>
		<h1>Add Trade</h1>
	</td>
	</tr>
	<tr class="highlight">
		<td>Fund</td>
		<td>Security Name</td>
		<td>Notes</td>
		<td>Trade Currency</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('fund_id'); ?></td>
			<td><?php echo $this->Form->input('sec_id'); ?></td>
			<td><?php echo $this->Form->input('notes'); ?></td>
			<td><?php echo $this->Form->input('currency'); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Trade Type</td>
		<td>Quantity</td>
		<td>Order Price</td>
		<td>Execution Price</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('trade_type_id'); ?></td>
			<td><?php echo $this->Form->input('quantity'); ?></td>
			<td><?php echo $this->Form->input('price'); ?></td>
			<td><?php echo $this->Form->input('execution_price'); ?></td>
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
	
	<tr><td colspan="4" style="text-align: center;"><?php echo $this->Form->end('Save Trade'); ?></td></tr>
</table>