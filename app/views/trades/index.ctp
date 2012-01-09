<!-- File: /app/views/trades/index.ctp -->

<table>
<tr>
<td><h1>Trade Blotter</h1></td>
</tr>
<tr class="altrow">
<?php echo $this->Form->create(null, array('url' => array('controller' => 'trades', 'action' => 'indexFiltered')));?>
<td><?php echo $this->Form->input('daterange',array('type'=>'select','options'=>array('-1 week'=>'Last Week','-1 month'=>'Last Month','-1 year'=>'Last Year'),'label'=>'Input Date Range'));?></td>
<td><?php echo $this->Form->input('fundchosen',array('type'=>'select','options'=>$funds,'label'=>'Choose Fund','empty'=>'All Funds'));?></td>
<td><?php echo $this->Form->input('brokerchosen',array('type'=>'select','options'=>$brokers,'label'=>'Choose Broker','empty'=>'All Brokers'));?></td>
<td><?php echo $this->Form->end('Filter');?></td>
</tr>
</table>

<table>
	<tr><td colspan="15"><h4>Showing latest week's trades below</h4></td></tr>
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
		<th>Currency</th>
		<th>Quantity</th>
		<th>Consideration</th>
		<th>Broker Contact</th>
		<th>Trade Date</th>
		<th>Price</th>
		<th>Cancelled Flag</th>
		<th>Executed Flag</th>
	</tr>

	<!-- Here is where we loop through our $trades array, printing out trade info -->

	<?php foreach ($trades as $trade): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $trade['Trade']['id']));?></td>
		<td><?php echo $this->Html->link('View', array('action' => 'view', $trade['Trade']['oid']));?></td>
		<td><?php echo $trade['Trade']['id']; ?></td>
		<td><?php echo $trade['Fund']['fund_name']; ?></td>
		<td><?php echo $trade['Sec']['sec_name']; ?></td>
		<td><?php echo $trade['TradeType']['trade_type']; ?></td>
		<td><?php echo $trade['Reason']['reason_desc']; ?></td>
		<td><?php echo $trade['Broker']['broker_name']; ?></td>
		<td><?php echo $trade['Trader']['trader_name']; ?></td>
		<td><?php echo $trade['Currency']['currency_iso_code']; ?></td>
		<td><?php echo number_format($trade['Trade']['quantity']); ?></td>
		<td style="text-align: right;"><?php echo number_format($trade['Trade']['consideration'],2); ?></td>
		<td><?php echo $trade['Trade']['broker_contact']; ?></td>
		<td style="width: 8%;"><?php echo $trade['Trade']['trade_date']; ?></td>
		<td><?php echo $trade['Trade']['price']; ?></td>
		<td><?php echo $trade['Trade']['cancelled']; ?></td>
		<td><?php echo $trade['Trade']['executed']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
