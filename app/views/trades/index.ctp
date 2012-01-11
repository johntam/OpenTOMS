<!-- File: /app/views/trades/index.ctp -->

<table>
<tr>
<td><h1>Trade Blotter</h1></td>
</tr>
<tr class="altrow">
	<?php echo $this->Form->create(null, array('url' => array('controller' => 'trades', 'action' => 'index')));?>
	<td>
		<div class="high">
			<?php echo $this->Form->input('daterange',array('default'=> $filter[0],'type'=>'select','options'=>array('-1 week'=>'Last Week','-1 month'=>'Last Month','-1 year'=>'Last Year'),'label'=>'Input Date Range'));?>
		</div>
	</td>
	<td>
		<div <?php if (isset($filter[1])) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('fundchosen',array('default'=> $filter[1],'type'=>'select','options'=>$funds,'label'=>'Choose Fund','empty'=>'All Funds'));?>
		</div>
	</td>
	<td>
		<div <?php if (isset($filter[2])) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('brokerchosen',array('default'=> $filter[2], 'type'=>'select','options'=>$brokers,'label'=>'Choose Broker','empty'=>'All Brokers'));?>
		</div>
	</td>
	<td><?php echo $this->Form->end('Filter');?></td>
	<?php
		if (empty($filter[1])) {
			$filter[1] = 0;
		}
		
		if (empty($filter[2])) {
			$filter[2] = 0;
		}
	?>
	<td><?php echo $html->link($html->image("/img/Excel-32.gif"), array('action' => 'index/'.$filter[0].'/'.$filter[1].'/'.$filter[2]), array('escape' => false));?></td>
</tr>
</table>

<table>
	<tr><td colspan="15"><h4>Filters in use above are shown in green background</h4></td></tr>
	<tr>
		<th>Edit</th>
		<th>View</th>
		<th>Id</th>
		<th>Fund</th>
		<th>Security</th>
		<th>Trade Type</th>
		<th>Reason</th>
		<th>Broker</th>
		<th>Trader</th>
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
