<!-- File: /app/views/ledgers/post.ctp -->

<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td><h1>General Ledger</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Ledger', array('action' => 'post')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('account_id', array('label'=>false, 'options'=>$accounts)); ?>
			<?php echo $this->Form->input('accounting_period', array('label'=>'Enter month','type'=>'date', 'dateFormat' => 'MY')); ?>
		</td>
		<td>
			<?php echo $this->Form->submit('View', array('name'=>'Submit', 'value' => 'View'));?>
			<?php echo $this->Form->submit('Post', array('name'=>'Submit', 'value' => 'Post'));?>
			<?php echo $this->Form->end(); ?>
		</td>
		
	</tr>
</table>


<table style="width: 40%;margin-left:30%;margin-right:30%;">	
	<tr>
		<th>Trade Date</th>
		<th>Fund</th>
		<th>Trade ID</th>
		<th>Trade Type</th>
		<th>Security</th>
		<th>Quantity</th>
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
				<?php echo $post['Fund']['fund_name']; ?>
			</td>
			<td>
				<?php echo $post['Trade']['id']; ?>
			</td>
			<td>
				<?php echo $post['TradeType']['trade_type']; ?>
			</td>
			<td>
				<?php echo $post['Sec']['sec_name']; ?>
			</td>
			<td>
				<?php echo $post['Trade']['quantity']; ?>
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