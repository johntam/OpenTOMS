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

<table>
	<tr>
		<td colspan="5">
			<h1>Edit Price</h1>
			<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Date</td>
		<td>Source</td>
		<td>Security Name</td>
		<td>Price</td>
	</tr>
	
		<tr>
			<td><?php echo $this->data['Price']['price_date']; ?></td>
			<td><?php echo $this->data['Price']['price_source']; ?></td>
			<td><?php echo $this->data['Sec']['sec_name']; ?></td>
			<td><?php echo $this->Form->input('price', array('label'=>false)); ?></td>
			<td><?php
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->input('price_date', array('type' => 'hidden')); 
				echo $this->Form->end('Update Price');
			?></td>
		</tr>
</table>
