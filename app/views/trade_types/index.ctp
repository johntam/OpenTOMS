<!-- File: /app/views/trade_types/index.ctp -->

<h1>Trade Types</h1>

<?php echo $this->Html->link('Add Trade Type', array('controller' => 'trade_types', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Trade Type</th>
		<th>Category</th>
		<th>Credit/Debit</th>
	</tr>

	<!-- Here is where we loop through our $tradetypes array, printing out tradetype info -->

	<?php foreach ($tradetypes as $tradetype): ?>
		<tr>
			<td><?php echo $tradetype['TradeType']['id']; ?></td>
			<td><?php echo $tradetype['TradeType']['trade_type']; ?></td>
			<td><?php echo $tradetype['TradeType']['category']; ?></td>
			<td><?php echo $tradetype['TradeType']['credit_debit']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>
