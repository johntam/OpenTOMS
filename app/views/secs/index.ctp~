<!-- File: /app/views/secs/index.ctp -->

<table>
	<tr>
		<td>
			<h1>Securities</h1>
			<div class="high">
				<?php echo $this->Html->link('Add Security', array('controller' => 'secs', 'action' => 'add')); ?>
			</div>
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
		<td colspan="9"><h4>
			Securities beginning with the letter <?php echo $this->params['pass'][0];?></h4>
		</td>
	</tr>
	
	<tr>
		<th>Edit</th>
		<th>View</th>
		<th>Security Name</th>
		<th>Ticker</th>
		<th>ISIN</th>
		<th>Sedol</th>
		<th>Currency</th>
		<th>Valpoint</th>
		<th>Active</th>
	</tr>

	<!-- Here is where we loop through our $secs array, printing out sec info -->

	<?php foreach ($secs as $sec): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td style="width: 5%;"><?php echo $this->Html->link('Edit', array('action' => 'edit', $sec['Sec']['id']));?></td>
		<td style="width: 5%;"><?php echo $this->Html->link('View', array('action' => 'view', $sec['Sec']['id']));?></td>
		<td><?php echo $sec['Sec']['sec_name']; ?></td>
		<td><?php echo $sec['Sec']['ticker']; ?></td>
		<td><?php echo $sec['Sec']['isin_code']; ?></td>
		<td><?php echo $sec['Sec']['sedol']; ?></td>
		<td><?php echo $sec['Currency']['currency_iso_code']; ?></td>
		<td><?php echo $sec['Sec']['valpoint']; ?></td>
		<td><?php 
					
					if ($sec['Sec']['act']==0) {
						echo $this->Html->link('Activate', array('action' => 'activate', $sec['Sec']['id'], $this->params['pass'][0]));
					} 
					else {
						echo $this->Html->link('Deactivate', array('action' => 'deactivate', $sec['Sec']['id'], $this->params['pass'][0]));
					}
					  
				?></td>
	</tr>
	<?php endforeach; ?>
</table>
