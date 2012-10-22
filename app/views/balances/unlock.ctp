<!--
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

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
