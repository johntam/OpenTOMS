<!-- File: /app/views/prices/fxrates.ctp -->

<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td><h1>Price FX Rates</h1></td>
	</tr>
	
	<tr class="altrow">
		<td colspan="4">
			<?php echo $this->Form->create('Price', array('action' => 'fxrates')); ?>
			<?php echo $this->Form->input('datefilter', array('label'=>'Enter pricing date','type'=>'date','default'=> $datefilter)); ?>
			<?php echo $this->Form->end('Filter'); ?>
		</td>
		
		
		</td>
	</tr>
</table>


<table style="width: 40%;margin-left:30%;margin-right:30%;">	
	<tr>
		<th>Date</th>
		<th>Source</th>
		<th>Currency</th>
		<th>Rate To USD</th>
	</tr>

	<?php echo $this->Form->create('Price', array('action' => 'fxrates/'.$datefilter)); ?>
	
	<?php foreach ($prices as $price): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td style="width: 25%;">
				<?php echo $datefilter; ?>
			</td>
			<td style="width: 10%;">
				<?php echo $this->Form->input("source_{$price['Sec']['id']}", array('label'=>false,'options' => array('DFLT'=>'DFLT','USER'=>'USER'),'default'=>$price['Price']['price_source'])); ?>
			</td>
			<td style="width: 10%;">
				<?php echo $price['Sec']['sec_name']; ?>
			</td>
			<td style="width: 15%;">
				<?php if ($price['Sec']['id'] == 1) {
						//USD
						echo '1.000000';
						echo $this->Form->input("price_{$price['Sec']['id']}", array('type'=>'hidden','default'=>1));
					}
					else {
						echo $this->Form->input("price_{$price['Sec']['id']}", array('label'=>false,'default'=>$price['Price']['fx_rate']));
					} ?>
				<?php echo $this->Form->input("priceid_{$price['Sec']['id']}", array('type' => 'hidden','default'=>$price['Price']['id'])); ?>
				<?php echo $this->Form->input("date_{$price['Sec']['id']}", array('label'=>false,'type'=>'hidden','default'=> $datefilter)); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<tr>
		<td colspan="4">
			<?php echo $this->Form->input("fx_date", array('type'=>'hidden','default'=> $datefilter)); ?>
			<?php echo $this->Form->end('Submit'); ?>
		</td>
	</tr>
</table>
