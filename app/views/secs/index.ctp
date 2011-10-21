<!-- File: /app/views/secs/index.ctp -->

<h1>Securities</h1>

<?php echo $this->Html->link('Add Security', array('controller' => 'secs', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Security Name</th>
		<th>Ticker</th>
		<th>Tradar ID</th>
		<th>Beta</th>
		<th>Country</th>
		<th>Industry</th>
		<th>Currency</th>
	</tr>

	<!-- Here is where we loop through our $secs array, printing out sec info -->

	<?php foreach ($secs as $sec): ?>
	<tr>
		<td><?php echo $sec['Sec']['id']; ?></td>
		<td><?php echo $sec['Sec']['sec_name']; ?></td>
		<td><?php echo $sec['Sec']['ticker']; ?></td>
		<td><?php echo $sec['Sec']['tradarid']; ?></td>
		<td><?php echo $sec['Sec']['beta']; ?></td>
		<td><?php echo $sec['Sec']['country']; ?></td>
		<td><?php echo $sec['Sec']['industry']; ?></td>
		<td><?php echo $sec['Sec']['currency']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
