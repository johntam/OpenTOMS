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

<script type="text/javascript">
				$(document).ready(function() {
					$('#fundpicker').change(function() {
						var selectfund = $('select option:selected').val();
						window.location = '/ValuationReports/index/' + selectfund;
					});
				});
</script>

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td colspan="2"><h1>Valuation Reports</h1></td>
	</tr>
	
	<tr class="altrow">
		<td width="30%">
			<?php echo $this->Form->create('ValuationReport', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds, 'id'=>'fundpicker','empty'=>'Choose Fund')); ?>
		</td>
		<td width="30%">
			<div class="high">
				Run Valuation Report
				<?php if (!empty($run_dates)) {
					echo $this->Form->input('run_date', array('label'=>false, 'options'=>$run_dates, 'style'=>'float:left;'));
					echo $this->Form->submit('Run', array('name'=>'Submit', 'value' => 'Run', 'style'=>'float:left;'));
				}
				else {
					echo '<div style="color: red;">No balance calculations have been found</div>';
				}
				?>
			</div>
		</td>
		<td></td>
		<?php echo $this->Form->end(); ?>
	</tr>
</table>	

<table style="width: 60%;margin-left:20%;margin-right:20%;">	
	<tr>
		<th>Status</th>
		<th>Fund</th>
		<th>Date</th>
		<th>Run Rime</th>
		<th>Show</th>
	</tr>
	
	<?php if (isset($reports)) { ?>
	
	<?php foreach ($reports as $report): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php 
					if ($report['ValuationReport']['final'] == 1) {
						echo 'Final';
					}
					else {
						echo 'Estimate';
					}
				?>
			</td>
			<td>
				<?php echo $report['Fund']['fund_name']; ?>
			</td>
			<td>
				<?php echo $report['ValuationReport']['pos_date']; ?>
			</td>
			<td>
				<?php echo $report['ValuationReport']['crd']; ?>
			</td>
			<td>
				<?php echo $this->Html->link('Show', array('action' => 'show', $report['Fund']['id'], $report['ValuationReport']['pos_date']));?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<?php }; ?>
</table>
