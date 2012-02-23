<!-- File: /app/views/ledgers/create.ctp -->
</br>
<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td colspan="6"><h1>Create New Ledger</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Ledger', array('action' => 'create')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('account_id', array('label'=>false, 'options'=>$accounts)); ?>
			<?php echo $this->Form->input('accounting_period', array('label'=>false,'type'=>'date', 'dateFormat' => 'MY')); ?>
		</td>
		<td colspan="2">
		</td>
	</tr>
	
	<tr>
		<td colspan="3" style="color: red;">
		Warning! This is a potentially destructive action. Creating a new ledger for this fund will wipe ALL month end data clean. 
		This action is meant to be used only in special circumstances, usually when a fund is migrated over to the system and a new starting point for NAV calculation needs to be set.
		This could also take a long time to finish.
		</br></br>
		Are you sure that you want to create a new starting ledger for this fund?
		</br></br>
		</td>
	</tr>
	<tr>
		<td>
		</td>
		<td>
		</td>
		<td style="width: 20%;">
			<div style="float:left; vertical-align:middle;"><?php echo $this->Form->submit('Yes', array('name'=>'Submit', 'value' => 'Yes'));?></div>
			<div style="float:right; vertical-align:middle;"><?php echo $this->Form->submit('No', array('name'=>'Submit', 'value' => 'No'));?></div>
		</td>
		<?php echo $this->Form->end(); ?>
	</tr>
</table>