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
			<td><?php echo $this->Form->input('fund_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sec_id',array('label'=>false, 'empty'=>'Select Security')); ?></td>
			<td><?php echo $this->Form->input('notes',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency_id',array('label'=>false, 'empty'=>' ')); ?></td>
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
		<td>Commission</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('broker_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('broker_contact',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('order_time',array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('commission',array('label'=>false, 'div'=>array('id'=>'TradeCommId'))); ?></td>
		</tr>
	

	<tr class="highlight">
		<td>Trade Reason</td>
		<td>Executed</td>
		<td>Cancelled</td>
		<td></td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('reason_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('executed',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('cancelled',array('label'=>false)); ?></td>
			<td></td>
		</tr>
	
	<?php 
		$this->Js->get('#TradeSecId')->event(
			'change',
			$this->Js->request(
				array('controller'=>'trades','action'=>'ajax_ccydropdown'),
				array('update' => '#TradeCurrencyId', 'dataExpression' => true, 'data' => '$("#TradeSecId").serialize()')
			)
		);
		
		$this->Js->get('#TradeQuantity')->event(
			'change',
			$this->Js->request(
				array('controller'=>'trades','action'=>'ajax_commission'),
				array('update' => '#TradeCommId', 'dataExpression' => true, 'data' => '$("#TradeQuantity").serialize()')
			)
		);
	?>
	
	<tr><td colspan="4" style="text-align: center;"><?php echo $this->Form->end('Save Trade'); ?></td></tr>
</table>