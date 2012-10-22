<!-- File: /app/views/prices/index.ctp -->

<table>
<tr>
<td><h1>Maintain Prices</h1></td>
</tr>
<tr class="altrow">

<td style="width: 30%;">
	<div <?php if (isset($secnamefiltered)) {echo 'class="high"';} ?>>
		<?php echo $this->Form->create('Price', array('action' => 'index/0/1/1/0')); ?>
		<?php echo $this->Form->input('secfilter',array('type'=>'text','label'=>'Enter a few characters of security name:','maxLength'=>10,'div'=>false));?>
		<?php echo $this->Form->end('Filter'); ?>
	</div>
</td>
<td style="width: 10%;"></td>
<td style="width: 30%;">
	<div <?php if (isset($datefiltered)) {echo 'class="high"';} ?>>
		<?php echo $this->Form->create('Price', array('action' => 'index/0/1/0/1')); ?>
		<?php echo $this->Form->input('datefilter', array('label'=>'Enter a date to filter on','type'=>'date','default'=> strtotime('-1 day'))); ?>
		<?php echo $this->Form->end('Filter'); ?>
	<div>
</td>

<td colspan="2"></td>
</tr>
</table>

<table>
	<tr>
		<td colspan="5">
			<?php
				if (isset($datefiltered)) {
					echo '<i>Filtering on date: </i><b>'.$datefiltered.'</b>';
					echo $this->Html->image("red_cross.jpeg", array("alt" => "Remove Filter",'url' => array('controller' => 'prices', 'action' => 'index', 0, 1, 0, 0)));
				}
				elseif (isset($secnamefiltered)) {
					echo '<i>Filtering on characters: </i><b>'.str_replace('%','',$secnamefiltered).'</b>';
					echo $this->Html->image("red_cross.jpeg", array("alt" => "Remove Filter",'url' => array('controller' => 'prices', 'action' => 'index', 0, 1, 0, 0)));
				}
				else {
					echo '<i>Showing prices between '.$todate.' weeks and '.$fromdate.' weeks ago</i> (';
					if ($todate == 0) {
						echo 'Earlier';
					}
					else {
						echo $this->Html->link('Earlier', array('action' => 'index', $todate-1, $fromdate-1, 0, 0));
					}
					echo '|'.$this->Html->link('Later', array('action' => 'index', $todate+1, $fromdate+1, 0, 0)).')';
				}
			?>	
		</td>
	</tr>
</table>

<table>	
	<tr>
		<th>Date</th>
		<th>Source</th>
		<th>Security</th>
		<th>Price</th>
		<th>Edit</th>
	</tr>

	<tr class="high2">
		<?php echo $this->Form->create(null, array('url' => array('controller' => 'prices', 'action' => 'add')));?>
		<td><?php echo $this->Form->input('price_date',array('label'=>false,'default'=> strtotime('-1 day'))); ?></td>
		<td><?php echo $this->Form->input('price_source',array('label'=>false, 'options' => array('DFLT'=>'DFLT','USER'=>'USER'))); ?></td>
		<td><?php echo $this->Form->input('sec_id',array('label'=>false)); ?></td>
		<td><?php echo $this->Form->input('price',array('type'=>'text','label'=>false)); ?></td>
		<td><?php echo $this->Form->end('Add'); ?></td>
	</tr>

	<?php foreach ($prices as $price): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $price['Price']['price_date']; ?></td>
		<td><?php echo $price['Price']['price_source']; ?></td>
		<td><?php echo $price['Sec']['sec_name']; ?></td>
		<td><?php echo $price['Price']['price']; ?></td>
		<td><?php echo $this->Html->image('edit.png', array('class'=>'editbutton', 'id' => 'edit_'.$price['Price']['id'])); ?></td>
	</tr>
	<tr id="<?php echo $price['Price']['id'];?>" style="display: none;">
		<td colspan="5">
			<table>
				<tr>
					<th>
						Provider
					</th>
					<th>
						Yahoo
					</th>
					<th>
						Google
					</th>
					<th>
						Bloomberg.com
					</th>
					<th>
						Manual Override
					</th>
					<th>
						Save
					</th>
				</tr>
				<tr>	
					<td>
						cell 1
					</td>
					<td>
						<?php echo $price['PDQ']['yahoo_price']; ?></br>
						<div style="font-size: 12px"><?php echo $price['PDQ']['yahoo_date']; ?></div>
					</td>
					<td>
						<?php echo $price['PDQ']['google_price']; ?></br>
						<div style="font-size: 12px"><?php echo $price['PDQ']['google_date']; ?></div>
					</td>
					<td>
						<?php echo $price['PDQ']['bloomberg_price']; ?></br>
						<div style="font-size: 12px"><?php echo $price['PDQ']['bloomberg_date']; ?></div>
					</td>
					<td>
						cell 5
					</td>
					<td>
						cell 6
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<script type="text/javascript">
	$(document).ready(function() {
		
		$('.editbutton').click(function() {
			var rowid = event.target.id;
			//rowid = rowid.substr(5);
			alert(rowid);
			
		});
		
		
		$('.savebutton').click(function() {
			var top = $(this).closest('tr');
			var price = $(top).find('.editprice').val();
			var final = $(top).find('.editfinal').val();
			var finaltext = $(top).find('.editfinal option:selected').text();
			var priceid = $(top).find('.priceid').val();
			var secid = $(top).find('.secid').val();
			var pricedate = $(top).find('.dispdate').html();
			$(top).addClass('highred');
			
			//send this to database
			$.post("/prices/ajax_edit?" + (new Date()).getTime(),
			{ secid : secid , pricedate : pricedate , price : price , final : final , id : priceid },
			function(data) {
				if (data.length > 0) {					
					if (data == "Y") {
						//saved ok
						$(top).find('#dispprice').html(price);
						$(top).find('#dispfinal').html(finaltext);
						$(top).find('.editprice').hide();
						$(top).find('.editfinal').hide();
						$(top).find('.savebutton').hide();
						$(top).find('.cancelbutton').hide();
						$(top).find('.editbutton').show();
						$(top).find('#dispprice').show();
						$(top).find('#dispfinal').show();
					}
					else {
						alert(data);
					}
				}
				$(top).removeClass('highred');
			},
			"text"
			);
			
			$('#uploadmorefiles').remove();
			refresh_attachlink(top);
		});
		
		
		$('.cancelbutton').click(function() {
			//cancel edit
			var top = $(this).closest('tr');
			$(top).find('.editprice').hide();
			$(top).find('.editfinal').hide();
			$(top).find('.savebutton').hide();
			$(top).find('.cancelbutton').hide();
			$(top).find('.editbutton').show();
			$(top).find('#dispprice').show();
			$(top).find('#dispfinal').show();
			
			$('#uploadmorefiles').remove();
			refresh_attachlink(top);
		});
	});
</script>