<!-- File: /app/views/balances/index.ctp -->
<?php $missingprices = false; ?>

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td colspan="7"><h1>Fix Balances</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('Balance', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('accounting_period', array('label'=>false,'type'=>'date', 'dateFormat' => 'MY')); ?>
		</td>
		<td>
			<div class="high">
				View month end balances
				<?php echo $this->Form->submit('View', array('name'=>'View'));?>
			</div>
		</td>
		<td>
			<div class="high">
				Calculate month end balances
				<?php echo $this->Form->submit('Calc', array('name'=>'Calc'));?>
			</div>
		</td>
		<td>
			<div class="high">
				(Un)Lock month end balances
				<?php 
					if (isset($locked)) {
						echo $this->Form->submit('Unlock', array('name'=>'Unlock'));
					}
					else {
						echo $this->Form->submit('Lock', array('name'=>'Lock'));
					}
				?>
				<div id="missingmessage" style="color: red;"></div>
			</div>
		</td>
		<td colspan="3">
		</td>
		<?php echo $this->Form->end(); ?>
	</tr>
	
	<?php if (isset($locked)) { echo '
	<tr><td><div style="color: red;">This month end is locked. Warning, unlocking month ends is a potentially destructive action. All future month ends will also be unlocked and unfinalised too.</div></td></tr>
	';} ?>
	
</table>	

<table style="width: 60%;margin-left:20%;margin-right:20%;">	
	<tr>
		<th>Fund</th>
		<th>Book</th>
		<th>Debit</th>
		<th>Credit</th>
		<th>Currency</th>
		<th>Security</th>
		<th>Quantity</th>
		<th>Price</th>
		<th>FX Rate</th>
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
				<?php echo $balance['Sec']['sec_name']; ?>
			</td>
			<td>
				<?php echo number_format($balance['Balance']['balance_quantity'],0); ?>
			</td>
			<td>
				<?php 	if ($balance['Account']['id'] > 1) {
							//No price for cash
						}
						else if (empty($balance['Price']['price'])) {
							echo '<font color="red">missing</font>';
							$missingprices = true;
						}
						else {
							echo number_format($balance['Price']['price'],2);
						} 
				?>
			</td>
			<td>
				<?php 	if ($balance['Account']['id'] == 1) {
							//No fx rate for securities
						}
						else if (empty($balance['Price']['fx_rate'])) {
							echo '<font color="red">missing</font>';
							$missingprices = true;
						}
						else {
							echo number_format($balance['Price']['fx_rate'],4);
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