<!-- File: /app/views/cash_ledgers/view.ctp -->

<?php echo $html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<script type="text/javascript">
				$(document).ready(function() {
					$('#dateinput').datepicker({ dateFormat: 'yy-mm-dd' });
					
					$('#dateinput').change(function() {
						var selectfund = $('select option:selected').val();
						$('#maintable').html('');
						$('#pleasewait').show();
						$('#CashLedgerForm').submit();
					});
					
					$('#fundpicker').change(function() {
						var selectfund = $('select option:selected').val();
						$('#maintable').html('');
						$('#pleasewait').show();
						$('#CashLedgerForm').submit();
						
					});
					
					$('#ccypicker').change(function() {
						var selectfund = $('select option:selected').val();
						$('#maintable').html('');
						$('#pleasewait').show();
						$('#CashLedgerForm').submit();
					});
					
					$('#leftarrow').click(function() {
						$('#maintable').html('');
						$('#pleasewait').show();
					});
					
					$('#rightarrow').click(function() {
						$('#maintable').html('');
						$('#pleasewait').show();
					});
				});
</script>

<table style="width: 80%;margin-left:10%;margin-right:10%;">
	<tr>
		<td colspan="3"><h1>Cash Ledgers</h1></td>
	</tr>
	
	<tr class="altrow">
		<td style="width: 25%;">
			<?php echo $this->Form->create('CashLedger', array('action' => 'index', 'id' => 'CashLedgerForm')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds, 'id'=>'fundpicker')); ?>
			<?php echo $this->Form->input('account_date', array('label'=>false, 'id'=>'dateinput', 'size'=>15, 'default'=>date('Y-m-d'), 'style'=>'float: left;')); ?>
			<?php echo $this->Form->submit('left.png', array('id'=>'leftarrow', 'name'=>'Backdate', 'value' => 'Backdate', 'style'=>'float:left;')); ?>
			<?php echo $this->Form->submit('right.png', array('id'=>'rightarrow', 'name'=>'Nextdate', 'value' => 'Nextdate', 'style'=>'float:left;')); ?>			
		</td>
		<td style="width: 25%;">
			<div class="high">
				Choose which cash ledger to view
				<?php echo $this->Form->input('ccy', array('label'=>false, 'options'=>$currencies, 'id'=>'ccypicker')); ?>
			</div>
		</td>
		<td>
		</td>
		<?php echo $this->Form->end(); ?>
	</tr>
</table>

<table style="width: 80%;margin-left:10%;margin-right:10%;" id="maintable">	
	<tr>
		<th>Security</th>
		<th>Date</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Quantity</th>
		<th>Trade OID</th>
	</tr>
		
	<tr class="high2">
		<td colspan="2">Balances brought forward</td>
		<td style="text-align: right"><?php echo number_format($debit,2); ?></td>
		<td style="text-align: right"><?php echo number_format($credit,2); ?></td>
		<td style="text-align: right"><?php echo number_format($qty,2); ?></td>
		<td></td>
	</tr>
	
	<?php if (!empty($cashledgers)) { ?>
	<?php $totdebits = $debit; $totcredits = $credit; $totqty = $qty; ?>
	
	<?php foreach ($cashledgers as $cashledger): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $cashledger['Sec2']['sec_name']; ?>
			</td>
			<td>
				<?php echo $cashledger['CashLedger']['trade_date']; ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($cashledger['CashLedger']['ledger_debit'],2); ?>
				<?php $totdebits += $cashledger['CashLedger']['ledger_debit']; ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($cashledger['CashLedger']['ledger_credit'],2); ?>
				<?php $totcredits += $cashledger['CashLedger']['ledger_credit']; ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($cashledger['CashLedger']['ledger_quantity'],2); ?>
				<?php $totqty += $cashledger['CashLedger']['ledger_quantity']; ?>
			</td>
			<td style="text-align: right;">
				<?php echo $this->Html->link($cashledger['Trade']['oid'], array('controller' => 'trades', 'action' => 'view',$cashledger['Trade']['oid']), array('escape' => false, 'target' => '_blank')); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr class="total">
		<td colspan="2">Balances carried forward</td>
		<td style="text-align: right"><?php echo number_format($totdebits,2); ?></td>
		<td style="text-align: right"><?php echo number_format($totcredits,2); ?></td>
		<td style="text-align: right"><?php echo number_format($totqty,2); ?></td>
		<td></td>
	</tr>
	
	<?php } else {
				echo '<tr class="high2"><td colspan="9">No cash journal entries posted for this date</td></tr>';
			} ?>
</table>
<div id='pleasewait' style='display: none; color: red; width: 80%;margin-left:10%;margin-right:10%;'>Please wait...</div>