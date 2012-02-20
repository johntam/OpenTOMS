<!-- File: /app/views/ledgers/index.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td colspan="6"><h1>Journal Posting</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			View Ledger
			<?php echo $this->Form->create('Ledger', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('account_id', array('label'=>false, 'options'=>$accounts)); ?>
			<?php echo $this->Form->input('accounting_period', array('label'=>false,'type'=>'date', 'dateFormat' => 'MY')); ?>
			<?php echo $this->Form->submit('View', array('name'=>'Submit', 'value' => 'View'));?>
			<?php echo $this->Form->end(); ?>
		</td>
		<td>
			Retrieve trade journal entries
			<?php echo $this->Form->create('Ledger', array('action' => 'post')); ?>
			<?php echo $this->Form->submit('Post', array('name'=>'Submit', 'value' => 'Post'));?>
			<?php echo $this->Form->end(); ?>
		</td>
		<td colspan="4">
		</td>
	</tr>
	<tr>
		<th>Fund</th>
		<th>Book</th>
		<th>Date</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Currency</th>
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
				<?php echo $ledger['Ledger']['ledger_debit']; ?>
			</td>
			<td>
				<?php echo $ledger['Ledger']['ledger_credit']; ?>
			</td>
			<td>
				<?php echo $ledger['Currency']['currency_iso_code']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>