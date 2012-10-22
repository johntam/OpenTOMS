<!-- File: /app/views/balances/unlock.ctp -->

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
		<td colspan="6"><h1>Unlock Balance Date</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Balance', array('action' => 'unlock')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('account_date', array('label'=>false,'id'=>'dateinput', 'size'=>15, 'default'=>date('Y-m-d'))); ?>
		</td>
		<td colspan="2">
		</td>
	</tr>
	
	<tr>
		<td colspan="3" style="color: red;">
		Warning! This is a potentially destructive action. Unlocking this month end balance will also unlock all future month end balances from this date onwards as well.
		This could mean that month end processing may have to be done again for a lot of months. Use with caution.
		</br></br>
		Are you sure that you want to unlock this month end?
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