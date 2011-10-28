<!-- File: /app/views/sec_types/index.ctp -->

<table>
	<tr>
		<td>
			<h1>Security Types</h1>
			<?php echo $this->Html->link('Add Security Type', array('controller' => 'sec_types', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>TRADAR Sec Type</th>
		<th>Sec Type Name</th>
		<th>Bond</th>
		<th>Deriv</th>
		<th>Exchrate</th>
		<th>CFD</th>
		<th>Equity</th>
		<th>FX</th>
		<th>OTC</th>
		<th>Bloomberg Yellow Key</th>
		<th>Supported</th>
	</tr>

	<?php foreach ($sectypes as $sectype): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td style="width: 5%;"><?php echo $sectype['SecType']['id']; ?></td>
			<td><?php echo $sectype['SecType']['sec_type']; ?></td>
			<td><?php echo $sectype['SecType']['sec_type_name']; ?></td>
			<td><?php echo $sectype['SecType']['bond']; ?></td>
			<td><?php echo $sectype['SecType']['deriv']; ?></td>
			<td><?php echo $sectype['SecType']['exchrate']; ?></td>
			<td><?php echo $sectype['SecType']['cfd']; ?></td>
			<td><?php echo $sectype['SecType']['equity']; ?></td>
			<td><?php echo $sectype['SecType']['fx']; ?></td>
			<td><?php echo $sectype['SecType']['otc']; ?></td>
			<td><?php echo $sectype['SecType']['yellow_key']; ?></td>
			<td><?php echo $sectype['SecType']['supported']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>
