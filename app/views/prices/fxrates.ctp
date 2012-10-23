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
