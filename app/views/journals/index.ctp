<!-- File: /app/views/journals/index.ctp -->

<?php echo $html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<?php echo $this->Html->script('journal_ajax.js',array('inline' => false)); ?>

<table style="width: 80%;margin-left:10%;margin-right:10%;">
	<tr>
		<td colspan="6"><h1>Journal Entry</h1></td>
	</tr>
	
	<tr class="altrow">
		<?php echo $this->Form->create('Journal', array('action' => 'index')); ?>
		<td>
			<?php echo $this->Form->input('fund_id', array('options'=>$funds, 'id'=>'fundpicker')); ?>
		</td>
		<td>
			<?php echo $this->Form->input('account_date', array('id'=>'dateinput', 'size'=>15, 'default'=>date('Y-m-d'), 'style'=>'float: left;')); ?>
		</td>
		<td>
			<?php echo $this->Form->input('trade_type_id', array('options'=>$tradeTypes, 'id'=>'ttpicker')); ?>
		</td>
		<td>
			<?php echo $this->Form->input('custodian_id', array('options'=>$custodians)); ?>
		</td>
		<td>
			<?php echo $this->Form->input('quantity', array('id'=>'quantitypicker')); ?>
		</td>
		<td>
			<?php echo $this->Form->input('currency_id', array('options'=>$currencies, 'id'=>'currencypicker')); ?>
		</td>
		<td>
			<?php echo $this->Form->input('notes'); ?>
		</td>
		<td>
			<?php echo $this->Form->end('Post'); ?>
		</td>
	</tr>
</table>

<table style="width: 80%;margin-left:10%;margin-right:10%;" id='journal_history'>
</table>