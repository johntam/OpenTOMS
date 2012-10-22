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

<?php echo $this->Html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $this->Html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<?php echo $this->Html->script('fundprices_ajax.js',array('inline' => false)); ?>
<?php echo $this->Html->script('fileuploader.js',array('inline' => false)); ?>

<table style="width: 70%;margin-left:15%;margin-right:15%;">
	<tr>
		<td><h1>Fund Prices and Estimates</h1></td>
	</tr>
	
	<form>
		<tr class="altrow">
			<td style="width: 30%;">
				Enter a few characters of security name:
				<input id="secfilter" type="text" />
			</td>
			<td style="width: 30%;">
				Enter a date to filter on:
				<input id="datefilter" />
			</td>
			<td>
				<noscript>			
					<p>Please enable JavaScript to use file uploader.</p>
					<!-- or put a simple form for upload here -->
				</noscript>
			</td>
		</tr>
	</form>
</table>

<table style="width: 70%;margin-left:15%;margin-right:15%;">	
	<tr>
		<th>Date</th>
		<th>Fund</th>
		<th>Price</th>
		<th>Source</th>
		<th>Attachments</th>
		<th>Edit</th>
	</tr>

	<tr class="high2">
		<?php echo $this->Form->create(null, array('url' => array('controller' => 'prices', 'action' => 'fundprices')));?>
		<td style="width: 15%;"><?php echo $this->Form->input('price_date_input',array('label'=>false, 'id'=>'pricedatepicker', 'size'=>15, 'default'=>date('Y-m-d'))); ?></td>
		<td style="width: 30%;"><?php echo $this->Form->input('sec_id',array('label'=>false, 'id'=>'secidpicker', 'style'=>'width: 80%', 'empty'=>'Choose Fund')); ?></td>
		<td style="width: 15%;text-align: right;"><?php echo $this->Form->input('price',array('type'=>'text','label'=>false,'size'=>15)); ?></td>
		<td style="width: 10%;text-align: center;"><?php echo $this->Form->input('final',array('label'=>false, 'options' => array('1'=>'Final','0'=>'Estimate'))); ?></td>
		<td style="text-align: center;">
			<div id="file-uploader">
		</td>
		<td style="width: 10%;text-align: center;"><div id="AddFundPriceButton"><?php echo $this->Form->end('Add'); ?></div></td>
	</tr>
</table>

<table style="width: 70%;margin-left:15%;margin-right:15%;" id="fund_price_table">
</table>
