<!-- File: /app/views/trades/index.ctp -->
<?php $paginator->options(array('url' => $this->passedArgs)); ?>
<table>
<tr>
<td><h1>Trade Blotter</h1></td>
</tr>
<tr class="altrow">
	<?php echo $this->Form->create('Trade', array('type'=>'get'));?>
	<td>
		<div <?php if (!$filter[3]) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('daterange',array('default'=> $filter[0],'type'=>'select','options'=>array('-1 week'=>'Last Week','-1 month'=>'Last Month','-1 year'=>'Last Year'),'label'=>'Input Date Range'));?>
		</div>
	</td>
	<td>
		<div <?php if ($filter[1]) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('fundchosen',array('default'=> $filter[1],'type'=>'select','options'=>$funds,'label'=>'Choose Fund','empty'=>'All Funds'));?>
		</div>
	</td>
	<td>
		<div <?php if ($filter[2]) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('brokerchosen',array('default'=> $filter[2], 'type'=>'select','options'=>$brokers,'label'=>'Choose Broker','empty'=>'All Brokers'));?>
		</div>
	</td>
	<td width="150px">
		<div <?php if ($filter[3]) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('oid',array('default'=> $filter[3], 'type'=>'text', 'label'=>'Original Order Id'));?>
		</div>
	</td>
	<td>
		<?php echo $this->Form->submit('Filter', array('name'=>'Submit', 'value' => 'Filter'));?>
	</td>
	<td>
		<div style="width: 35px; height: 35px;">
			<?php echo $this->Form->submit('Excel-32.gif', array('name'=>'Submit', 'value' => 'Excel'));?>
		</div>
	</td>
	<?php echo $this->Form->end();?>
</tr>
</table>

<table>
	<tr><td colspan="17"><h4>Filters in use above are shown in green background</h4></td></tr>
	<tr>
		<th>Edit</th>
		<th>View</th>
		<th><?php echo $this->Paginator->sort('Id', 'Trade.id'); ?></th>
		<th><?php echo $this->Paginator->sort('Orig Id', 'Trade.oid'); ?></th>
		<th><?php echo $this->Paginator->sort('Fund', 'Fund.fund_name'); ?></th>
		<th><?php echo $this->Paginator->sort('Security', 'Sec.sec_name'); ?></th>
		<th><?php echo $this->Paginator->sort('Trade Type', 'TradeType.trade_type'); ?></th>
		<th><?php echo $this->Paginator->sort('Reason', 'Reason.reason_desc'); ?></th>
		<th><?php echo $this->Paginator->sort('Broker', 'Broker.broker_name'); ?></th>
		<th><?php echo $this->Paginator->sort('Trader', 'Trader.trader_name'); ?></th>
		<th><?php echo $this->Paginator->sort('Currency', 'Currency.currency_iso_code'); ?></th>
		<th><?php echo $this->Paginator->sort('Quantity', 'Trade.quantity'); ?></th>
		<th><?php echo $this->Paginator->sort('Consideration', 'Trade.consideration'); ?></th>
		<th><?php echo $this->Paginator->sort('Trade Date', 'Trade.trade_date'); ?></th>
		<th><?php echo $this->Paginator->sort('Settlement Date', 'Trade.settlement_date'); ?></th>
		<th><?php echo $this->Paginator->sort('Execution Price', 'Trade.execution_price'); ?></th>
		<th><?php echo $this->Paginator->sort('Cancelled', 'Trade.cancelled'); ?></th>
		<th><?php echo $this->Paginator->sort('Executed', 'Trade.executed'); ?></th>
	</tr>

	<!-- Here is where we loop through our $trades array, printing out trade info -->

	<?php foreach ($trades as $trade): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $trade['Trade']['id']));?></td>
		<td><?php echo $this->Html->link('View', array('action' => 'view', $trade['Trade']['oid']));?></td>
		<td><?php echo $trade['Trade']['id']; ?></td>
		<td><?php echo $trade['Trade']['oid']; ?></td>
		<td><?php echo $trade['Fund']['fund_name']; ?></td>
		<td><?php echo $trade['Sec']['sec_name']; ?></td>
		<td><?php echo $trade['TradeType']['trade_type']; ?></td>
		<td><?php echo $trade['Reason']['reason_desc']; ?></td>
		<td><?php echo $trade['Broker']['broker_name']; ?></td>
		<td><?php echo $trade['Trader']['trader_name']; ?></td>
		<td><?php echo $trade['Currency']['currency_iso_code']; ?></td>
		<td><?php echo number_format($trade['Trade']['quantity']); ?></td>
		<td style="text-align: right;"><?php echo number_format($trade['Trade']['consideration'],2); ?></td>
		<td style="width: 8%;"><?php echo $trade['Trade']['trade_date']; ?></td>
		<td style="width: 8%;"><?php echo $trade['Trade']['settlement_date']; ?></td>
		<td><?php echo $trade['Trade']['execution_price']; ?></td>
		<td><?php echo $trade['Trade']['cancelled']; ?></td>
		<td><?php echo $trade['Trade']['executed']; ?></td>
	</tr>
	<?php endforeach; ?>
	
</table>