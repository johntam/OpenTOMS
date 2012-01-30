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
		<td>Notes</td>
		<td>Trade Currency</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('fund_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sec_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('notes',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency_id',array('label'=>false)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Trade Type</td>
		<td>Quantity</td>
		<td>Order Price</td>
		<td>Execution Price</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('trade_type_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('quantity',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('price',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('execution_price',array('label'=>false)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Decision Time</td>
		<td>Trade Date</td>
		<td>Settlement Date</td>
		<td>Trader</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('decision_time',array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('trade_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('settlement_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('trader_id',array('label'=>false)); ?></td>
		</tr>
		
	<tr class="highlight">
		<td>Broker</td>
		<td>Broker Contact</td>
		<td>Order Time</td>
		<td>Trade Reason</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('broker_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('broker_contact',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('order_time',array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('reason_id',array('label'=>false)); ?></td>
		</tr>
	
	
	<tr class="highlight">
		<td>Commission</td>
		<td>Tax</td>
		<td>Other Costs</td>
		<td>Total Consideration</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('commission',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('tax',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('other_costs',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('consideration',array('label'=>false)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Executed</td>
		<td>Cancelled</td>
		<td></td>
		<td>Notional Value</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('executed',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('cancelled',array('label'=>false)); ?></td>
			<td></td>
			<td><?php echo $this->Form->input('notional_value',array('label'=>false)); ?></td>
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

