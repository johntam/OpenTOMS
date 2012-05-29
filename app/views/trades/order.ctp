<!-- File: /app/views/trades/order.ctp -->	

<?php echo $this->Form->create('Trade', array('id' => 'TradeInputForm')); ?>

<table>	
	<tr><td>
		<h1>Add Order</h1>
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
			<td><div id="sec_link" style="float:right; vertical-align:middle;"></div><?php echo $this->Form->input('sec_id',array('label'=>false, 'empty'=>'Select Security')); ?></td>
			<td><?php echo $this->Form->input('notes',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency_id',array('label'=>false, 'empty'=>' ')); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Trade Type</td>
		<td>Order Quantity</td>
		<td>Order Price</td>
		<td>Notional Value</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('trade_type_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('order_qty',array('label'=>false,'id'=>'order_qty')); ?></td>
			<td><?php echo $this->Form->input('order_price',array('label'=>false,'id'=>'order_price')); ?></td>
			<td><div id="notional"></div></td>
		</tr>
	
	<tr class="highlight">
		<td>Custodian</td>
		<td>Trade Date</td>
		<td>Trader</td>
		<td>Trade Reason</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('custodian_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('trade_date_input',array('type'=>'text','label'=>false, 'id'=>'tradedatepicker', 'size'=>15, 'default'=>date('Y-m-d'))); ?></td>
			<td><?php echo $this->Form->input('trader_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('reason_id',array('label'=>false)); ?></td>
		</tr>
		
	<tr class="highlight" id="head4">
		<td>Broker</td>
		<td>Broker Contact</td>
		<td>Decision Time</td>
		<td>Order Time</td>
	</tr>
	
		<tr class="altrow" id="row4">
			<td><?php echo $this->Form->input('broker_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('broker_contact',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('decision_time_date',array('type'=>'text', 'label'=>false, 'id'=>'decisiondatepicker', 'size'=>12, 'default'=>date('Y-m-d'), 'style'=>'float:left;'));
					  echo $this->Form->input('decision_time_time',array('type'=>'text', 'label'=>false, 'default'=>'00:00', 'size'=>5, 'style'=>'float:left;')); ?></td>
			<td><?php echo $this->Form->input('order_time_date',array('type'=>'text', 'label'=>false, 'id'=>'orderdatepicker', 'size'=>12, 'default'=>date('Y-m-d'), 'style'=>'float:left;'));
					  echo $this->Form->input('order_time_time',array('type'=>'text', 'label'=>false, 'default'=>'00:00', 'size'=>5, 'style'=>'float:left;')); ?></td>
		</tr>
	
	<tr><td colspan="4" style="text-align: center;"><?php echo $this->Form->end('Save Order'); ?></td></tr>
</table>

<?php echo $this->Html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $this->Html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<?php echo $this->Html->script('order_ajax.js',array('inline' => false)); ?>