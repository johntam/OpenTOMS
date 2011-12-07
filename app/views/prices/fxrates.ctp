<!-- File: /app/views/prices/fxrates.ctp -->

<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td><h1>Price FX Rates</h1></td>
	</tr>
	
	<tr class="altrow">
		<td colspan="4">
			<?php echo $this->Form->create('Price', array('action' => 'fxrates')); ?>
			<?php echo $this->Form->input('datefilter', array('label'=>'Enter pricing date','type'=>'date','default'=> $datefiltered)); ?>
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

	<?php echo $this->Form->create('Price', array('action' => 'fxrates')); ?>
	
	<?php foreach ($prices as $price): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td style="width: 25%;">
				<?php echo $this->Form->input("date_{$price['Sec']['id']}", array('label'=>false,'type'=>'date','default'=> $datefiltered)); ?>
			</td>
			<td style="width: 10%;">
				<?php echo $this->Form->input("source_{$price['Sec']['id']}", array('label'=>false,'options' => array('DFLT'=>'DFLT','USER'=>'USER'),'default'=>$price['Price']['price_source'])); ?>
			</td>
			<td style="width: 10%;">
				<?php echo $price['Sec']['sec_name']; ?>
			</td>
			<td style="width: 15%;">
				<?php echo $this->Form->input("price_{$price['Sec']['id']}", array('label'=>false,'default'=>$price['Price']['price'])); ?>
				<?php echo $this->Form->input("priceid_{$price['Sec']['id']}", array('type' => 'hidden','default'=>$price['Price']['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<tr>
		<td colspan="4">
			<?php echo $this->Form->input("datefilter", array('type' => 'hidden','default'=>$datefiltered)); ?>
			<?php echo $this->Form->end('Submit'); ?>
		</td>
	</tr>
</table>
