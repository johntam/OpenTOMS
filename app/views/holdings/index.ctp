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

<table style="width: 50%;margin-left:25%;margin-right:25%;">
	<tr>
		<td><h1>Holdings Report</h1></td>
	</tr>
	
	<?php echo $this->Form->create('Holding', array('action' => 'index', 'id'=>'HoldingsForm')); ?>
	<tr class="altrow">
		<td class="high" style="width: 40%">
			Fund
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds, 'id'=>'fundinput','empty'=>'Choose Fund')); ?>
		</td>
		<td>
			Holdings Date
			<?php echo $this->Form->input('holdings_date', array('label'=>false,'id'=>'dateinput', 'size'=>15, 'default'=>date('Y-m-d'),'style'=>'float: left;')); ?>
		</td>
	</tr>
	<?php echo $this->Form->end(); ?>
</table>

<table style="width: 50%;margin-left:25%;margin-right:25%;">	
	<tr>
		<th style="width: 75%;">Security</th>
		<th>Quantity</th>
	</tr>
	
	<?php if (isset($holdings)) { ?>
	<?php foreach ($holdings as $holding): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $holding['Sec']['sec_name']; ?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($holding['0']['quantity'],2); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	<?php }; ?>
</table>

<?php echo $html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#dateinput').datepicker({ dateFormat: 'yy-mm-dd' });
		
		$('#dateinput').change(function() {
			$('#HoldingsForm').submit();
		});
		
		$('#fundinput').change(function() {
			$('#HoldingsForm').submit();
		});
	});
</script>
