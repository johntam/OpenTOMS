<!-- File: /app/views/ledgers/create.ctp -->

<?php echo $html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<script type="text/javascript">
				$(document).ready(function() {
					$('#dateinput').datepicker({ dateFormat: 'yy-mm-dd' });
				});
</script>

</br>
<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td><h1>Create New Ledger</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Ledger', array('action' => 'create')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds,'empty'=>'Choose Fund')); ?>
			<?php echo $this->Form->input('account_date', array('label'=>false,'id'=>'dateinput')); ?>
		</td>
	</tr>
	
	<tr>
		<td style="color: red;">
		Warning! This is a destructive action. Creating a new ledger for this fund will wipe ALL data that has been posted for this fund. 
		This action is meant to be used only in extreme circumstances, e.g. when a fund's NAV has become unreconcilable and it would be
		easier to start again from scratch. N.B. that all finalised balances will be wiped as well. This operation could also take a long 
		time to finish. MAKE SURE THAT DATABASE HAS BEEN BACKED UP FIRST!
		</br></br>
		Are you sure that you want to create a new general ledger for this fund?
		</br></br>
		</td>
	</tr>
	<tr>
		<td style="width: 20%;">
			<div style="float:right; vertical-align:middle;"><?php echo $this->Form->submit('Yes', array('name'=>'Submit', 'value' => 'Yes'));?></div>
			<div style="float:right; vertical-align:middle;"><?php echo $this->Form->submit('No', array('name'=>'Submit', 'value' => 'No'));?></div>
		</td>
		<?php echo $this->Form->end(); ?>
	</tr>
</table>