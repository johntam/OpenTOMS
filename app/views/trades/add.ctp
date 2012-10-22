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

<script type="text/javascript">
	$(document).ready(function() {
		$('#decisiontime').datetimepicker();
	});
</script>
<?php echo $this->Form->create('Trade', array('id' => 'TradeInputForm')); ?>

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
			<td><div id="sec_link" style="float:right; vertical-align:middle;"></div><?php echo $this->Form->input('sec_id',array('label'=>false, 'empty'=>'Select Security')); ?></td>
			<td><?php echo $this->Form->input('notes',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency_id',array('label'=>false, 'empty'=>' ')); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Trade Type</td>
		<td>Quantity</td>
		<td>Execution Price</td>
		<td>Executed</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('trade_type_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('quantity',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('execution_price',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('executed',array('label'=>false, 'checked'=>true)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Custodian</td>
		<td>Trade Date</td>
		<td>Settlement Date<img src="/img/ajax-busy.gif" id="settdate_busy"/></td>
		<td>Trader</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('custodian_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('trade_date_input',array('label'=>false, 'id'=>'tradedatepicker', 'size'=>15, 'default'=>date('Y-m-d'))); ?></td>
			<td><?php echo $this->Form->input('settlement_date_input',array('label'=>false, 'id'=>'settlementdatepicker', 'size'=>15, 'default'=>date('Y-m-d'))); ?></td>
			<td><?php echo $this->Form->input('trader_id',array('label'=>false)); ?></td>
		</tr>
		
	<tr class="highlight" id="head4">
		<td>Broker</td>
		<td>Broker Contact</td>
		<td></td>
		<td>Trade Reason</td>
	</tr>
	
		<tr class="altrow" id="row4">
			<td><?php echo $this->Form->input('broker_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('broker_contact',array('label'=>false)); ?></td>
			<td></td>
			<td><?php echo $this->Form->input('reason_id',array('label'=>false)); ?></td>
		</tr>
	

	<tr class="highlight" id="head5">
		<td><div>Commission<img src="/img/ajax-busy.gif" id="commission_busy"/></div></td>
		<td><div>Tax<img src="/img/ajax-busy.gif" id="tax_busy"/></div></td>
		<td><div>Other Costs<img src="/img/ajax-busy.gif" id="othercosts_busy"/></div></td>
		<td><div>Total Consideration<img src="/img/ajax-busy.gif" id="consideration_busy"/></div></td>
	</tr>

		<tr class="altrow" id="row5">
			<td><?php echo $this->Form->input('commission',array('label'=>false, 'div'=>array('id'=>'TradeCommId'))); ?></td>
			<td><?php echo $this->Form->input('tax',array('label'=>false, 'div'=>array('id'=>'TradeTaxId'))); ?></td>
			<td><?php echo $this->Form->input('other_costs',array('label'=>false, 'div'=>array('id'=>'TradeOtherCostsId'))); ?></td>
			<td><?php echo $this->Form->input('consideration',array('label'=>false, 'div'=>array('id'=>'TradeConsiderationId'))); ?></td>
		</tr>
	
	
	<tr class="highlight" id="head6">
		<td>Decision Time</td>
		<td>Order Time</td>
		<td>Accrued Interest<img src="/img/ajax-busy.gif" id="accrued_busy"/></td>
		<td><div>Notional Value<img src="/img/ajax-busy.gif" id="notional_busy"/></div></td>
	</tr>

		<tr class="altrow" id="row6">
			<td><?php echo $this->Form->input('decision_time_date',array('type'=>'text', 'label'=>false, 'id'=>'decisiondatepicker', 'size'=>12, 'default'=>date('Y-m-d'), 'style'=>'float:left;'));
					  echo $this->Form->input('decision_time_time',array('type'=>'text', 'label'=>false, 'default'=>'00:00', 'size'=>5, 'style'=>'float:left;')); ?></td>
			<td><?php echo $this->Form->input('order_time_date',array('type'=>'text', 'label'=>false, 'id'=>'orderdatepicker', 'size'=>12, 'default'=>date('Y-m-d'), 'style'=>'float:left;'));
					  echo $this->Form->input('order_time_time',array('type'=>'text', 'label'=>false, 'default'=>'00:00', 'size'=>5, 'style'=>'float:left;')); ?></td>
			<td><?php echo $this->Form->input('accrued',array('label'=>false)); ?><div class="error-message" id="accrued_error"></div></td>
			<td><?php echo $this->Form->input('notional_value',array('label'=>false)); ?></td>
		</tr>
		
	<tr class="highlight" id="head7">
		<td>Cancelled</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

		<tr class="altrow" id="row7">
			<td><?php echo $this->Form->input('cancelled',array('label'=>false)); ?></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		
	<tr><td colspan="4" style="text-align: center;"><?php echo $this->Form->end('Save Trade'); ?></td></tr>
</table>

<?php echo $this->Html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $this->Html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<?php echo $this->Html->script('trade_ajax.js',array('inline' => false)); ?>
