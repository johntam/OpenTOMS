<!-- File: /app/views/trade_types/edit.ctp -->

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td colspan="3">
			<h1>Edit Trade Type</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Trade Type</td>
		<td>Debit Account</td>
		<td>Credit Account</td>
	</tr>
	
	<tr>
		<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
		<td><?php echo $this->Form->input('trade_type', array('label'=>false)); ?></td>
		<td><?php echo $this->Form->input('debit_account_id', array('label'=>false, 'options'=>$accountlist)); ?></td>
		<td><?php echo $this->Form->input('credit_account_id', array('label'=>false, 'options'=>$accountlist)); ?></td>
		<td><?php
			echo $this->Form->input('id', array('type' => 'hidden')); 
			echo $this->Form->end('Update Trade Type');
		?></td>
	</tr>
</table>
