<!--
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->	

<?php echo $html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<script type="text/javascript">
				$(document).ready(function() {
					$('#from_date').datepicker({ dateFormat: 'yy-mm-dd' });
					$('#to_date').datepicker({ dateFormat: 'yy-mm-dd' });
				});
</script>

<?php $paginator->options(array('url' => $this->passedArgs)); ?>
<table style="width: 90%;margin-left:5%;margin-right:5%;">
<tr>
<td colspan="6"><h1>Trade Blotter</h1></td>
</tr>
<tr class="altrow">
	<?php echo $this->Form->create('Trade', array('type'=>'get'));?>
	<td width="350px">
		<div <?php if (!$filter[5]) {echo 'class="high"';} ?>>
			<div style="float:left; width: 160px">From:<?php echo $this->Form->input('from_date', array('label'=>false,'id'=>'from_date', 'size'=>15, 'default'=>$filter[1])); ?></div>
			<div style="float:left; width: 160px">To:<?php echo $this->Form->input('to_date', array('label'=>false,'id'=>'to_date', 'size'=>15, 'default'=>$filter[0])); ?></div>
		</div>
	</td>
	<td>
		<div <?php if ($filter[2]) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('fundchosen',array('default'=> $filter[2],'type'=>'select','options'=>$funds,'label'=>'Choose Fund','empty'=>'All Funds'));?>
		</div>
	</td>
	<td>
		<div <?php if ($filter[3]) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('brokerchosen',array('default'=> $filter[3], 'type'=>'select','options'=>$brokers,'label'=>'Choose Broker','empty'=>'All Brokers'));?>
		</div>
	</td>
	<td>
		<div <?php if ($filter[4]) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('secchosen',array('default'=> $filter[4], 'type'=>'select','options'=>$secs,'label'=>'Choose Security','empty'=>'All Securities'));?>
		</div>
	</td>
	<td width="150px">
		<div <?php if ($filter[5]) {echo 'class="high"';} ?>>
			<?php echo $this->Form->input('oid',array('default'=> $filter[5], 'type'=>'text', 'label'=>'Original Order Id'));?>
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

<table style="width: 90%;margin-left:5%;margin-right:5%;">
	<tr><td colspan="19"><h4>Filters in use above are shown in green background</h4></td></tr>
	<tr>
		<th>Edit</th>
		<th>View</th>
		<th>Copy</th>
		<th><?php echo $this->Paginator->sort('Id', 'Trade.id'); ?></th>
		<th><?php echo $this->Paginator->sort('Orig Id', 'Trade.oid'); ?></th>
		<th><?php echo $this->Paginator->sort('Fund', 'Fund.fund_name'); ?></th>
		<th><?php echo $this->Paginator->sort('Security', 'Sec.sec_name'); ?></th>
		<th><?php echo $this->Paginator->sort('Trade Type', 'TradeType.trade_type'); ?></th>
		<th><?php echo $this->Paginator->sort('Broker', 'Broker.broker_name'); ?></th>
		<th><?php echo $this->Paginator->sort('Trader', 'Trader.trader_name'); ?></th>
		<th><?php echo $this->Paginator->sort('Currency', 'Currency.currency_iso_code'); ?></th>
		<th><?php echo $this->Paginator->sort('Order Quantity', 'Trade.order_quantity'); ?></th>
		<th><?php echo $this->Paginator->sort('Filled Quantity', 'Trade.quantity'); ?></th>
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
		<td><?php echo $this->Html->link('Copy', array('action' => 'copy', $trade['Trade']['id']));?></td>
		<td><?php echo $trade['Trade']['id']; ?></td>
		<td><?php echo $trade['Trade']['oid']; ?></td>
		<td><?php echo $trade['Fund']['fund_name']; ?></td>
		<td><?php echo $trade['Sec']['sec_name']; ?></td>
		<td><?php echo $trade['TradeType']['trade_type']; ?></td>
		<td><?php echo $trade['Broker']['broker_name']; ?></td>
		<td><?php echo $trade['Trader']['trader_name']; ?></td>
		<td><?php echo $trade['Currency']['currency_iso_code']; ?></td>
		<td><?php echo number_format($trade['Trade']['order_quantity']); ?></td>
		<td><?php echo number_format($trade['Trade']['quantity']); ?></td>
		<td style="text-align: right;"><?php echo number_format($trade['Trade']['consideration'],2); ?></td>
		<td style="width: 8%;"><?php echo $trade['Trade']['trade_date']; ?></td>
		<td style="width: 8%;"><?php echo $trade['Trade']['settlement_date']; ?></td>
		<td><?php echo number_format($trade['Trade']['execution_price'],4); ?></td>
		<td><?php echo $trade['Trade']['cancelled']; ?></td>
		<td><?php echo $trade['Trade']['executed']; ?></td>
	</tr>
	<?php endforeach; ?>
	
</table>
