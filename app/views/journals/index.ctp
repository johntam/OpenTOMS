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
