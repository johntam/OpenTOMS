<!-- File: /app/views/brokers/edit.ctp -->

<table>
	<tr>
		<td colspan="3">
			<h1>Edit Broker</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Broker Code</td>
		<td>Broker Long Name</td>
		<td>Standard Commission Rate</td>
	</tr>
	
		<tr>
			<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
			<td><?php echo $this->Form->input('broker_name', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('broker_long_name', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('commission_rate', array('label'=>false)); ?></td>
			<td><?php
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->end('Update Broker');
			?></td>
		</tr>
</table>