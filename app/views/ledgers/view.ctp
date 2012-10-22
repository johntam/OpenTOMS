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
					
					$('#dateinput').change(function() {
						var selectfund = $('select option:selected').val();
						$('#maintable').html('');
						$('#pleasewait').show();
						window.location = '/ledgers/index/' + selectfund + '/' + $('#dateinput').val();
					});
					
					$('#fundpicker').change(function() {
						var selectfund = $('select option:selected').val();
						$('#maintable').html('');
						$('#pleasewait').show();
						window.location = '/ledgers/index/' + selectfund + '/0' ;
					});
					
					$('#leftarrow').click(function() {
						$('#maintable').html('');
						$('#pleasewait').show();
					});
					
					$('#rightarrow').click(function() {
						$('#maintable').html('');
						$('#pleasewait').show();
					});
				});
</script>

<table style="width: 80%;margin-left:10%;margin-right:10%;">
	<tr>
		<td colspan="4"><h1>Journal Posting</h1></td>
	</tr>
	
	<tr class="altrow">
		<td style="width: 25%;">
			<?php echo $this->Form->create('Ledger', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds, 'id'=>'fundpicker','empty'=>'Choose Fund')); ?>
			<?php echo $this->Form->input('account_date', array('label'=>false, 'id'=>'dateinput', 'size'=>15, 
																'default'=>date('Y-m-d'), 'style'=>'float: left;')); ?>
			<?php echo $this->Form->submit('left.png', array('id'=>'leftarrow', 'name'=>'Backdate', 'value' => 'Backdate', 'style'=>'float:left;')); ?>
			<?php echo $this->Form->submit('right.png', array('id'=>'rightarrow', 'name'=>'Nextdate', 'value' => 'Nextdate', 'style'=>'float:left;')); ?>			
		</td>
		<td style="width: 25%;">
			<div class="high">
				Post trades to general ledger
				<?php echo $this->Form->submit('Post', array('name'=>'Submit', 'value' => 'Post'));?>
			</div>
		</td>
		<td style="width: 25%;">
			<div class="careful">
				Create new general ledger
				<?php echo $this->Form->submit('Create', array('name'=>'Submit', 'value' => 'Create'));?>
			</div>
		</td>
		<td>
		</td>
		<?php echo $this->Form->end(); ?>
	</tr>
</table>

<table style="width: 80%;margin-left:10%;margin-right:10%;" id="maintable">	
	<tr>
		<th>Fund</th>
		<th>Custodian</th>
		<th>Book</th>
		<th>Date</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Currency</th>
		<th>Security</th>
		<th>Quantity</th>
		<th>Trade OID</th>
	</tr>
	
	<?php if (!empty($ledgers)) { $last_cust = $ledgers[0]['Custodian']['custodian_name']; ?>
	
	<?php foreach ($ledgers as $ledger): ?>
		<?php if ($ledger['Custodian']['custodian_name'] <> $last_cust) {
				echo '<tr class="highred"><td colspan="10"></td></tr>';
			} ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $ledger['Fund']['fund_name']; ?>
			</td>
			<td>
				<?php echo $ledger['Custodian']['custodian_name']; ?>
			</td>
			<td>
				<?php echo $ledger['Account']['account_name']; ?>
			</td>
			<td>
				<?php echo $ledger['Ledger']['trade_date']; ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($ledger['Ledger']['ledger_debit'],2); ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($ledger['Ledger']['ledger_credit'],2); ?>
			</td>
			<td>
				<?php echo $ledger['Currency']['currency_iso_code']; ?>
			</td>
			<td>
				<?php echo $ledger['Sec']['sec_name']; ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($ledger['Ledger']['ledger_quantity'],0); ?>
			</td>
			<td style="text-align: right;">
				<?php echo $this->Html->link($ledger['Trade']['oid'], array('controller' => 'trades', 'action' => 'view',$ledger['Trade']['oid']), array('escape' => false, 'target' => '_blank')); 
				$last_cust = $ledger['Custodian']['custodian_name']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<?php } else {
				echo '<tr class="high2"><td colspan="10">No trades posted for this date</td></tr>';
			} ?>
</table>
<div id='pleasewait' style='display: none; color: red; width: 80%;margin-left:10%;margin-right:10%;'>Please wait...</div>
