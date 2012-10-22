<!-- File: /app/views/funds/index.ctp -->

<table>
	<tr>
		<td>
		<h1>Funds</h1>
		<?php echo $this->Html->link('Add Fund', array('controller' => 'funds', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>Fund Name</th>
		<th>Fund Currency</th>
		<th>Management Fee</th>
		<th>Edit</th>
	</tr>

	<!-- Here is where we loop through our $funds array, printing out fund info -->

	<?php foreach ($funds as $fund): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $fund['Fund']['id']; ?></td>
		<td><?php echo $fund['Fund']['fund_name']; ?></td>
		<td><?php echo $fund['Currency']['currency_iso_code']; ?></td>
		<td><?php echo $fund['Fund']['management_fee']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $fund['Fund']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
