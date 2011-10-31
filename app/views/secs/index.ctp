<!-- File: /app/views/secs/index.ctp -->

<table>
	<tr>
		<td>
			<h1>Securities</h1>

			<?php echo $this->Html->link('Add Security', array('controller' => 'secs', 'action' => 'add')); ?>
		</td>
	</tr>
	
	<tr>
		<td colspan="8">
			<?php foreach (range('A', 'Z') as $letter) {
				echo $this->Html->link($letter, array('controller' => 'secs', 'action' => 'index',$letter)).'|';
				}
			?>
		</td>
	</tr>
	
	<tr>
		<td colspan="8"><h4>
			Securities beginning with the letter <?php echo $this->params['pass'][0];?></h4>
		</td>
	</tr>
	
	<tr>
		<th>Edit</th>
		<th>View</th>
		<th>Security Name</th>
		<th>Ticker</th>
		<th>Tradar ID</th>
		<th>Currency</th>
		<th>Valpoint</th>
	</tr>

	<!-- Here is where we loop through our $secs array, printing out sec info -->

	<?php foreach ($secs as $sec): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td style="width: 5%;"><?php echo $this->Html->link('Edit', array('action' => 'edit', $sec['Sec']['id']));?></td>
		<td style="width: 5%;"><?php echo $this->Html->link('View', array('action' => 'view', $sec['Sec']['id']));?></td>
		<td><?php echo $sec['Sec']['sec_name']; ?></td>
		<td><?php echo $sec['Sec']['ticker']; ?></td>
		<td><?php echo $sec['Sec']['tradarid']; ?></td>
		<td><?php echo $sec['Sec']['currency']; ?></td>
		<td><?php echo $sec['Sec']['valpoint']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
