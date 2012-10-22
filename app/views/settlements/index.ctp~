<!-- File: /app/views/settlements/index.ctp -->


<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td>
			<h1>Settlement Rules</h1>
			<?php 
				echo $this->Form->create('Settlement');
				echo $this->Form->input('sec_type_id',array('label'=>false, 'default'=>$sectype_id));
				echo $this->Form->end('Choose Sec Type'); 
			?>
		</td>
		<td></td>
		<td></td>
	</tr>
	
	<tr>
		<th>Country</th>
		<th>Settlement Days</th>
		<th></th>
	</tr>
	
	<tr>
		<?php echo $this->Form->create('Settlement', array('action' => 'add')); ?>
		<td style="width: 20%;">	
			<?php echo $this->Form->input('country_id', array('label'=>false)); ?>
		</td>
		<td style="width: 5%;">
			<?php echo $this->Form->input('settlement_days', array('label'=>false,'options' => array(0=>'T+0', 1=>'T+1',2=>'T+2',3=>'T+3',4=>'T+4',5=>'T+5'))); ?>
		</td>
		<td style="width: 8%;">
			<?php echo $this->Form->input('sec_type_id', array('type'=>'hidden', 'default'=>$sectype_id)); ?>
			<?php echo $this->Form->end('Add'); ?>
		</td>
		<td></td>
	</tr>

	<tr>
		<td colspan="3">
			<font color='red'>
				The default settlement period for the security type above is T+
				<?php echo $default_settlement;?>
				. This can by be overidden on a country by country basis below.
			</font>
		</td>
	</tr>
	
	<?php foreach ($settlements as $settlement): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php
					echo $settlement['Country']['country_name'];
				?>
			</td>
			<td>
				<?php
					echo 'T+'.$settlement['Settlement']['settlement_days'];
				?>
			</td>
			<td>
				<?php
					echo $this->Html->link('Del', '/settlements/delete/'.$settlement['Settlement']['id'].'/'.$sectype_id);
				?>
			</td>
		</tr>
	<?php endforeach; ?>

</table>