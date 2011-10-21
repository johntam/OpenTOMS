<!-- File: /app/views/funds/index.ctp -->

<h1>Funds</h1>

<?php echo $this->Html->link('Add Fund', array('controller' => 'funds', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Fund Name</th>
		<th>Fund Currency</th>
		<th>Management Fee</th>
	</tr>

	<!-- Here is where we loop through our $funds array, printing out fund info -->

	<?php foreach ($funds as $fund): ?>
	<tr>
		<td><?php echo $fund['Fund']['id']; ?></td>
		<td><?php echo $fund['Fund']['fund_name']; ?></td>
		<td><?php echo $fund['Fund']['fund_currency']; ?></td>
		<td><?php echo $fund['Fund']['management_fee']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
