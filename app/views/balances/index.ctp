<!-- File: /app/views/balances/index.ctp -->

<?php echo $html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<script type="text/javascript">
				$(document).ready(function() {
					$('#dateinput').datepicker({ dateFormat: 'yy-mm-dd' });
				});
</script>
<?php $missingprices = false; ?>

<table style="width: 80%;margin-left:10%;margin-right:10%;">
	<tr>
		<td colspan="9"><h1>Lock Balances</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Balance', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('account_date', array('label'=>false,'id'=>'dateinput', 'size'=>15, 'default'=>date('Y-m-d'))); ?>
		</td>
		<td>
			<div class="high">
				View month end balances
				<?php echo $this->Form->submit('View', array('name'=>'Submit', 'value' => 'View'));?>
			</div>
		</td>
		<td>
			<div class="high">
				Calculate month end balances
				<?php if (!empty($calcdates)) {
					echo $this->Form->input('calc_date', array('label'=>false, 'options'=>$calcdates));
					echo $this->Form->submit('Calc', array('name'=>'Submit', 'value' => 'Calc'));
				}
				else {
					echo '<div style="color: red;">No ledgers posted since last locked balance date.</div>';
				}
				?>
			</div>
		</td>
		<td>
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
		
		<?php echo $this->Form->end(); ?>
	</tr>
	
	<?php if (isset($locked)) { echo '
	<tr><td><div style="color: red;">This month end is locked</div></td></tr>
	';} ?>
	
</table>	

<table style="width: 80%;margin-left:10%;margin-right:10%;" id="baltable">	
	<tr>
		<th>Fund</th>
		<th>Book</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Currency</th>
		<th>FX Rate</th>
		<th>Security</th>
		<th>Quantity</th>
		<th>Price</th>
	</tr>
	
	<?php if (isset($balances)) { ?>
	
	<?php foreach ($balances as $balance): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $balance['Fund']['fund_name']; ?>
			</td>
			<td>
				<?php echo $balance['Account']['account_name']; ?>
			</td>
			<td>
				<?php echo number_format($balance['Balance']['balance_debit'],2); ?>
			</td>
			<td>
				<?php echo number_format($balance['Balance']['balance_credit'],2); ?>
			</td>
			<td>
				<?php echo $balance['Currency']['currency_iso_code']; ?>
			</td>
			<td>
				<?php 	if (empty($balance['PriceFX']['fx_rate'])) {
							echo "<input type='text' maxLength='10' id='fx_".$balance['Currency']['sec_id'].
								"' value='missing' class='missingprices' name='data[Balance][Pricebox][fx_".$balance['Currency']['sec_id']."]' />";
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
			<td>
				<?php echo number_format($balance['Balance']['balance_quantity'],0); ?>
			</td>
			<td>
				<?php 	if ($balance['Account']['id'] > 1) {
							//No price allowed for cash books
						}
						else if ($balance['Sec']['sec_type_id'] == 2) {
							//No price allowed for cash securities in non-cash books either
						}
						else if (empty($balance['Price']['price'])) {
							echo "<input type='text' maxLength='10' id='pr_".$balance['Sec']['id'].
								"' value='missing' class='missingprices' name='data[Balance][Pricebox][pr_".$balance['Sec']['id']."]' />";
							$missingprices = true;
						}
						else {
							echo number_format($balance['Price']['price'],2);
						} 
				?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<?php }; ?>
</table>
<?php 	if ($missingprices) {
			echo '<div id="missing" style="visibility:hidden">Y</div>';
		}
		else {
			echo '<div id="missing" style="visibility:hidden">N</div>';
		}
?>

<?php echo $this->Html->script('balance_ajax.js',array('inline' => false)); ?>