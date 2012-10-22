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
