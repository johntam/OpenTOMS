<!-- File: /app/views/trades/index.ctp -->

<h2>Trades</h2>

<?php echo $this->Html->link('Add Trade', array('controller' => 'trades', 'action' => 'add')); ?>

<table>
<tr>
<?php echo $this->Form->create();?>
<td><?php echo $this->Form->input('daterange',array('type'=>'select','options'=>array('-1 week'=>'Last Week','-1 month'=>'Last Month','-1 year'=>'Last Year'),'label'=>'Input Date Range'));?></td>
<td><?php echo $this->Form->input('fundchosen',array('type'=>'select','options'=>$funds,'label'=>'Choose Fund'));?></td>
<td><?php echo $this->Form->input('brokerchosen',array('type'=>'select','options'=>$brokers,'label'=>'Choose Broker'));?></td>
<td><?php echo $this->Form->end('Filter');?></td>
</tr>
</table>



<table>
	<tr>
		<th>Edit</th>
		<th>View</th>
		<th>Id</th>
		<th>Fund Id</th>
		<th>Security Id</th>
		<th>Trade Type Id</th>
		<th>Reason Id</th>
		<th>Broker Id</th>
		<th>Trader Id</th>
		<th>Quantity</th>
		<th>Broker Contact</th>
		<th>Trade Date</th>
		<th>Price</th>
		<th>Cancelled Flag</th>
		<th>Executed Flag</th>
	</tr>

	<!-- Here is where we loop through our $trades array, printing out trade info -->

	<?php foreach ($trades as $trade): ?>
	<tr>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $trade['Trade']['id']));?></td>
		<td><?php echo $this->Html->link('View', array('action' => 'view', $trade['Trade']['oid']));?></td>
		<td><?php echo $trade['Trade']['id']; ?></td>
		<td><?php echo $trade['Fund']['fund_name']; ?></td>
		<td><?php echo $trade['Sec']['sec_name']; ?></td>
		<td><?php echo $trade['TradeType']['trade_type']; ?></td>
		<td><?php echo $trade['Reason']['reason_desc']; ?></td>
		<td><?php echo $trade['Broker']['broker_name']; ?></td>
		<td><?php echo $trade['Trader']['trader_name']; ?></td>
		<td><?php echo $trade['Trade']['quantity']; ?></td>
		<td><?php echo $trade['Trade']['broker_contact']; ?></td>
		<td><?php echo $trade['Trade']['trade_date']; ?></td>
		<td><?php echo $trade['Trade']['price']; ?></td>
		<td><?php echo $trade['Trade']['cancelled']; ?></td>
		<td><?php echo $trade['Trade']['executed']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
