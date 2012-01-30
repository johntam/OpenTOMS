<!-- File: /app/views/trade_types/index.ctp -->

<table>
	<tr>
		<td>
			<h1>Trade Types</h1>
				<div class="high">
					<?php echo $this->Html->link('Add Trade Type', array('controller' => 'trade_types', 'action' => 'add')); ?>
				</div>
		</td>
	</tr>
	<tr>
		<th style="width: 10%">Edit</th>
		<th>Trade Type</th>
		<th>Debit Account</th>
		<th>Credit Account</th>
	</tr>

	<?php foreach ($tradetypes as $tradetype): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $tradetype['TradeType']['id']));?></td>
			<td><?php echo $tradetype['TradeType']['trade_type']; ?></td>
			<td><?php echo $tradetype['Debit']['account_name']; ?></td>
			<td><?php echo $tradetype['Credit']['account_name']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>