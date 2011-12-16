<!-- File: /app/views/holidays/index.ctp -->


<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td>
			<h1>Holiday Dates</h1>
			<?php 
				echo $this->Form->create('Holiday');
				echo $this->Form->input('country_id',array('label'=>false, 'default'=>$countryid));
				echo $this->Form->end('Choose Country'); 
			?>
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<th>Country</th>
		<th>Day</th>
		<th>Month</th>
		<th>Description</th>
		<th></th>
	</tr>
	
	<tr>
		<?php echo $this->Form->create('Holiday', array('action' => 'add')); ?>
		<td style="width: 20%;">	
			<?php echo $this->Form->input('country_id', array('label'=>false, 'default'=>$countryid)); ?>
		</td>
		<td style="width: 5%;">
			<?php echo $this->Form->input('holiday_day', array('label'=>false)); ?>
		</td>
		<td style="width: 5%;">
			<?php echo $this->Form->input('holiday_month', array('label'=>false, 'options' => array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12))); ?>
		</td>
		<td>
			<?php echo $this->Form->input('holiday_desc', array('label'=>false)); ?>
		</td>
		<td style="width: 8%;">
			<?php echo $this->Form->end('Add'); ?>
		</td>
	</tr>

	<?php foreach ($holidays as $holiday): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td>
			<?php echo $holiday['Country']['country_name']; ?>
		</td>
		<td>
			<?php echo $holiday['Holiday']['holiday_day']; ?>
		</td>
		<td>
			<?php echo $holiday['Holiday']['holiday_month']; ?>
		</td>
		<td>
			<?php echo $holiday['Holiday']['holiday_desc']; ?>
		</td>
	</tr>
	<?php endforeach; ?>
	
</table>