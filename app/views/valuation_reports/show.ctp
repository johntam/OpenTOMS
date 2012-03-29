<!-- File: /app/views/valuation_reports/show.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td><h1>Valuation Report</h1></td>
	</tr>
	
	<tr class="high2">
		<td><b><?php echo $valuations[0]['Fund']['fund_name']; ?></b></td>
		<td><b>Report Date: <?php echo $valuations[0]['ValuationReport']['pos_date']; ?></b></td>
		<td>
			<?php echo $this->Form->create('ValuationReport', array('action' => 'index/'.$valuations[0]['ValuationReport']['fund_id'])); ?>
			<?php echo $this->Form->submit('Back To List', array('name'=>'Submit', 'value' => 'Back'));?>
			<?php echo $this->Form->end(); ?>
		</td>
	</tr>
</table>	

<table style="width: 60%;margin-left:20%;margin-right:20%;">	
	<tr>
		<th>Security</th>
		<th>Quantity</th>
		<th>Price</th>
		<th>Market Val (Local)</th>
		<th>Currency</th>
		<th>FX Rate</th>
		<th>Market Val (<?php echo $fundccyname; ?>)</th>
	</tr>
	
	<?php if (isset($valuations)) { $totmvfund = 0; ?>
	
	<?php foreach ($valuations as $valuation): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $valuation['Sec']['sec_name']; ?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($valuation['ValuationReport']['quantity'],2); ?>
			</td>
			<td style="text-align: right;">
				<?php if ($valuation['Sec']['sec_type_id'] > 2) {
						echo number_format($valuation['ValuationReport']['price'],5);
				}?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($valuation['ValuationReport']['mkt_val_local'],2); ?>
			</td>
			<td style="text-align: right;">
				<?php echo $valuation['Currency']['currency_iso_code']; ?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($valuation['ValuationReport']['fx_rate'],4); ?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($valuation['ValuationReport']['mkt_val_fund'],2); 
					$totmvfund += $valuation['ValuationReport']['mkt_val_fund']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
		<tr class="total">
			<td colspan="6"></td>
			<td style="text-align: right;"><?php echo number_format($totmvfund,2); ?></td>
		</tr>
	<?php }; ?>
</table>