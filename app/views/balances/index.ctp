<!-- File: /app/views/balances/index.ctp -->

<?php echo $html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<script type="text/javascript">
				$(document).ready(function() {
					$('#dateinput').datepicker({ dateFormat: 'yy-mm-dd' });
					
					$('#dateinput').change(function() {
						var selectfund = $('select option:selected').val();
						$('#maintable').html('');
						$('#pleasewait').show();
						window.location = '/balances/index/' + selectfund + '/' + $('#dateinput').val();
					});
					
					$('#fundpicker').change(function() {
						var selectfund = $('select option:selected').val();
						$('#maintable').html('');
						$('#pleasewait').show();
						window.location = '/balances/index/' + selectfund + '/0' ;
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
<?php $missingprices = false; ?>

<table style="width: 80%;margin-left:10%;margin-right:10%;">
	<tr>
		<td colspan="4">
			<h1>Lock Balances</h1>
		</td>
	</tr>
	
	<tr class="altrow">
		<td style="width: 25%;">
			<?php echo $this->Form->create('Balance', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds, 'id'=>'fundpicker')); ?>
			<?php echo $this->Form->input('account_date', array('label'=>false,'id'=>'dateinput', 'size'=>15, 'default'=>date('Y-m-d'),
																'style'=>'float: left;')); ?>
			<?php echo $this->Form->submit('left.png', array('id'=>'leftarrow', 'name'=>'Backdate', 'value' => 'Backdate', 'style'=>'float:left;')); ?>
			<?php echo $this->Form->submit('right.png', array('id'=>'rightarrow', 'name'=>'Nextdate', 'value' => 'Nextdate', 'style'=>'float:left;')); ?>
		</td>
		<td style="width: 25%;">
			<div class="high">
				Calculate month end balances
				<?php if (!empty($calcdates)) {
					echo $this->Form->input('calc_date', array('label'=>false, 'options'=>$calcdates, 'style'=>'float:left;'));
					echo $this->Form->submit('Calc', array('name'=>'Submit', 'value' => 'Calc', 'style'=>'float:left;'));
				}
				else {
					echo '<div style="color: red;">No ledgers posted since last locked balance date.</div>';
				}
				?>
			</div>
		</td>
		<td style="width: 25%;">
			<div class="careful">
				(Un)Lock month end balances
				<?php 
					if (isset($locked)) {
						echo $this->Form->submit('Unlock', array('name'=>'Submit', 'value' => 'Unlock', 'div'=>array('id'=>'UnLockButtonID')));
					}
					else {
						if (!empty($balances)) {
							echo $this->Form->submit('Lock', array('name'=>'Submit', 'value' => 'Lock', 'div'=>array('id'=>'LockButtonID')));
						}
					}
				?>
				<div id="missingmessage" style="color: red;"></div>
			</div>
		</td>
		<td></td>
		<?php echo $this->Form->end(); ?>
	</tr>
	
	<?php if (isset($message)) { echo '<tr><td colspan="4"><div style="color: red;">'.$message.'</div></td></tr>';} ?>
	
</table>	

<table style="width: 80%;margin-left:10%;margin-right:10%;" id="maintable">
	<tr>
		<th>Fund</th>
		<th>Custodian</th>
		<th>Book</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>FX Rate</th>
		<th>Security</th>
		<th>Quantity</th>
		<th>Price</th>
	</tr>
	
	<?php if (isset($balances[0])) { $last_cust = $balances[0]['Custodian']['custodian_name']; ?>
	
	<?php foreach ($balances as $balance): ?>
		<?php if ($balance['Custodian']['custodian_name'] <> $last_cust) {
				echo '<tr class="highred"><td colspan="9"></td></tr>';
			} ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $balance['Fund']['fund_name']; ?>
			</td>
			<td>
				<?php echo $balance['Custodian']['custodian_name']; ?>
			</td>
			<td>
				<?php echo $balance['Account']['account_name']; ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($balance['Balance']['balance_debit'],2); ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($balance['Balance']['balance_credit'],2); ?>
			</td>
			<td style="text-align: right">
				<?php 	if (($balance['Account']['id'] == 1) && ($balance['Sec']['sec_type_id'] <> 2)) {
							//No fx rate for non-cash securities
						}
						else if ($balance['Balance']['balance_quantity'] == 0) {
							//No pricing for zero holdings
						}
						else if (empty($balance['PriceFX']['fx_rate'])) {
									echo "<input type='text' maxLength='10' id='fx_".$balance['Currency']['sec_id'].
										"' value='enter fx' class='missingprices' name='data[Balance][Pricebox][fx_".$balance['Currency']['sec_id']."]' />";
									$missingprices = true;
						}
						else {
							echo number_format($balance['PriceFX']['fx_rate'],4);
						}
				?>
			</td>
			<td>
				<?php echo $balance['Sec']['sec_name']; ?>
			</td>
			<td style="text-align: right">
				<?php echo number_format($balance['Balance']['balance_quantity'],0); ?>
			</td>
			<td style="text-align: right">
				<?php 	if ($balance['Account']['id'] > 1) {
							//No price allowed for cash books
						}
						else if ($balance['Balance']['balance_quantity'] == 0) {
							//No pricing for zero holdings
						}
						else if ($balance['Sec']['sec_type_id'] == 2) {
							//No price allowed for cash securities in non-cash books either
						}
						else if (empty($balance['Price']['price'])) {
							echo "<input type='text' maxLength='10' id='pr_".$balance['Sec']['id'].
								"' value='enter price' class='missingprices' name='data[Balance][Pricebox][pr_".$balance['Sec']['id']."]' />";
							$missingprices = true;
						}
						else {
							echo number_format($balance['Price']['price'],2);
						} 
				?>
			</td>
		</tr>
		<?php $last_cust = $balance['Custodian']['custodian_name']; ?>
	<?php endforeach; ?>
	
	<?php }; ?>
</table>
<div id='pleasewait' style='display: none; color: red; width: 80%;margin-left:10%;margin-right:10%;'>Please wait...</div>

<?php 	if ($missingprices) {
			echo '<div id="missing" style="visibility:hidden">Y</div>';
		}
		else {
			echo '<div id="missing" style="visibility:hidden">N</div>';
		}
?>

<?php echo $this->Html->script('balance_ajax.js',array('inline' => false)); ?>