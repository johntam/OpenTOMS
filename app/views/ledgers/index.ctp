<!-- File: /app/views/ledgers/index.ctp -->

<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td><h1>General Ledger</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Ledger', array('action' => 'post')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('accounting_period', array('label'=>'Enter month','type'=>'date', 'dateFormat' => 'MY')); ?>
			<?php echo $this->Form->submit('View', array('name'=>'Submit', 'value' => 'View'));?>
			<?php echo $this->Form->submit('Post', array('name'=>'Submit', 'value' => 'Post'));?>
			<?php echo $this->Form->end(); ?>
		</td>
		
	</tr>
</table>


<table style="width: 40%;margin-left:30%;margin-right:30%;">	
	<tr>
		<th>Trade Date</th>
		<th>Trade ID</th>
		<th>Trade Type</th>
		<th>Debit Account Id</th>
		<th>Credit Account Id</th>
		<th>Consideration</th>
		<th>Currency</th>
	</tr>
	
	<?php if (isset($posts)) { ?>
	
	<?php foreach ($posts as $post): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $post['Trade']['trade_date']; ?>
			</td>
			<td>
				<?php echo $post['Trade']['id']; ?>
			</td>
			<td>
				<?php echo $post['Trade']['trade_type_id']; ?>
			</td>
			<td>
				<?php echo $post['TradeType']['debit_account_id']; ?>
			</td>
			<td>
				<?php echo $post['TradeType']['credit_account_id']; ?>
			</td>
			<td>
				<?php echo $post['Trade']['consideration']; ?>
			</td>
			<td>
				<?php echo $post['Currency']['currency_iso_code']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	<?php } ?>
</table>