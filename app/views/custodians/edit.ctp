<!-- File: /app/views/custodians/edit.ctp -->

<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td colspan="3">
			<h1>Edit Custodian</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Custodian Short Name</td>
		<td>Custodian Long Name</td>
	</tr>
	
	<tr>
		<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
		<td style="width: 15%;"><?php echo $this->Form->input('custodian_name', array('label'=>false)); ?></td>
		<td><?php echo $this->Form->input('custodian_long_name', array('label'=>false,'size'=>50)); ?></td>
	</tr>
	
	<tr>
		<td colspan="2"><?php
			echo $this->Form->input('id', array('type' => 'hidden')); 
			echo $this->Form->end('Update Custodian');
		?></td>
	</tr>
</table>