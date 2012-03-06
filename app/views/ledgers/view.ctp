<!-- File: /app/views/ledgers/index.ctp -->

<?php echo $html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<script type="text/javascript">
				$(document).ready(function() {
					$('#dateinput').datepicker({ dateFormat: 'yy-mm-dd' });
				});
</script>

<table style="width: 80%;margin-left:10%;margin-right:10%;">
	<tr>
		<td colspan="9"><h1>Journal Posting</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Ledger', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('account_id', array('label'=>false, 'options'=>$accounts)); ?>
			<?php echo $this->Form->input('account_date', array('label'=>false, 'id'=>'dateinput', 'size'=>15, 'default'=>date('Y-m-d'))); ?>		
		</td>
		<td>
			<div class="high">
				View trial balance for month
				<?php echo $this->Form->submit('View', array('name'=>'Submit', 'value' => 'View'));?>
			</div>
		</td>
		<td>
			<div class="high">
				Post trades to general ledger
				<?php echo $this->Form->submit('Post', array('name'=>'Submit', 'value' => 'Post'));?>
			</div>
		</td>
		<td>
			<div class="careful">
				Create new ledger
				<?php echo $this->Form->submit('Create', array('name'=>'Submit', 'value' => 'Create'));?>
			</div>
		</td>
		<?php echo $this->Form->end(); ?>
	</tr>
</table>	

<table style="width: 80%;margin-left:10%;margin-right:10%;">	
	<tr>
		<th>Fund</th>
		<th>Book</th>
		<th>Date</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Currency</th>
		<th>Security</th>
		<th>Quantity</th>
		<th>Trade OID</th>
	</tr>
	
	<?php foreach ($ledgers as $ledger): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $ledger['Fund']['fund_name']; ?>
			</td>
			<td>
				<?php echo $ledger['Account']['account_name']; ?>
			</td>
			<td>
				<?php echo $ledger['Ledger']['trade_date']; ?>
			</td>
			<td>
				<?php echo number_format($ledger['Ledger']['ledger_debit'],2); ?>
			</td>
			<td>
				<?php echo number_format($ledger['Ledger']['ledger_credit'],2); ?>
			</td>
			<td>
				<?php echo $ledger['Currency']['currency_iso_code']; ?>
			</td>
			<td>
				<?php echo $ledger['Sec']['sec_name']; ?>
			</td>
			<td>
				<?php echo number_format($ledger['Ledger']['ledger_quantity'],0); ?>
			</td>
			<td style="text-align: right;">
				<?php echo $this->Html->link($ledger['Trade']['oid'], array('controller' => 'trades', 'action' => 'view',$ledger['Trade']['oid']), array('escape' => false, 'target' => '_blank')); ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>