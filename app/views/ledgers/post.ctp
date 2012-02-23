<!-- File: /app/views/ledgers/post.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td colspan="6"><h1>Journal Posting</h1></td>
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
		<th>Trade Date</th>
		<th>Fund</th>
		<th>Trade ID</th>
		<th>Trade Type</th>
		<th>Security</th>
		<th>Quantity</th>
		<th>Consideration</th>
		<th>Currency</th>
		<th>Security</th>
		<th>Quantity</th>
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
			<td>
				<?php echo $post['Sec']['sec_name']; ?>
			</td>
			<td>
				<?php echo $post['Trade']['quantity']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<?php }; ?>
</table>