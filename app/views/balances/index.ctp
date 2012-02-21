<!-- File: /app/views/balances/index.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td colspan="6"><h1>Fix Balances</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Ledger', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('account_id', array('label'=>false, 'options'=>$accounts)); ?>
			<?php echo $this->Form->input('accounting_period', array('label'=>false,'type'=>'date', 'dateFormat' => 'MY')); ?>
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
		<td colspan="5">
		</td>
		<?php echo $this->Form->end(); ?>
	</tr>
</table>	

<table style="width: 60%;margin-left:20%;margin-right:20%;">	
	<tr>
		<th>Fund</th>
		<th>Book</th>
		<th>Date</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Currency</th>
		<th>Security</th>
		<th>Quantity</th>
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
				<?php echo $ledger['Ledger']['ledger_date']; ?>
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
		</tr>
	<?php endforeach; ?>
</table>