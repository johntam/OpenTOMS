<!-- File: /app/views/prices/fundprices.ctp -->
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
		<td style="width: 10%;"></td>
		<td style="width: 30%;">
			Enter a date to filter on:
			<input id="datefilter" />
		</td>
	</form>

	<td colspan="2">
		<div id="host" style="display: none;"><?php echo $host; ?></div>
		<div id="username" style="display: none;"><?php echo $username; ?></div>
		<div id="password" style="display: none;"><?php echo $password; ?></div>
		<div id="database" style="display: none;"><?php echo $database; ?></div>
		
		<div id="file-uploader">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>
	</td>
	</tr>
</table>

<table style="width: 70%;margin-left:15%;margin-right:15%;">	
	<tr>
		<th>Date</th>
		<th>Security</th>
		<th>Price</th>
		<th>Final</th>
		<th>Edit</th>
	</tr>

	<tr class="high2">
		<?php echo $this->Form->create(null, array('url' => array('controller' => 'prices', 'action' => 'fundprices')));?>
		<td style="width: 15%;"><?php echo $this->Form->input('price_date_input',array('label'=>false, 'id'=>'pricedatepicker', 'size'=>15, 'default'=>date('Y-m-d'))); ?></td>
		<td style="width: 45%;"><?php echo $this->Form->input('sec_id',array('label'=>false, 'style'=>'width: 80%')); ?></td>
		<td style="width: 15%;text-align: right;"><?php echo $this->Form->input('price',array('type'=>'text','label'=>false,'size'=>15)); ?></td>
		<td style="width: 10%;text-align: center;"><?php echo $this->Form->input('final',array('label'=>false, 'options' => array('1'=>'Yes','0'=>'No'))); ?></td>
		<td style="text-align: center;"><?php echo $this->Form->end('Add'); ?></td>
	</tr>
</table>

<table style="width: 70%;margin-left:15%;margin-right:15%;" id="fund_price_table">
</table>