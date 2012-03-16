<?php if (!empty($journals)) { ?>
	<tr>
		<th>Date</th>
		<th>Type</th>
		<th>Amount</th>
		<th>Currency</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
	<?php foreach ($journals as $journal): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $journal['Journal']['trade_date']; ?>
			</td>
			<td>
				<?php echo $journal['TradeType']['trade_type']; ?>
			</td>
			<td>
				<?php echo $journal['Journal']['quantity']; ?>
			</td>
			<td>
				<?php echo $journal['Currency']['currency_iso_code']; ?>
			</td>
			<td style="text-align: right;">
				<?php echo $this->Html->link('Edit', array('controller' => 'journals', 'action' => 'edit')); ?>
			</td>
			<td style="text-align: right;">
				<?php echo $this->Html->link('Delete', array('controller' => 'journals', 'action' => 'delete')); ?>
			</td>
		</tr>
<?php endforeach; } ?>