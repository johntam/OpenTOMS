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

<table style="width: 70%;margin-left:15%;margin-right:15%;">
	<tr>
		<td colspan="5">
			<h1>Trade Types</h1></br>
				<div>
					<?php echo $this->Html->link('Add Trade Type', array('controller' => 'trade_types', 'action' => 'add')); ?>
				</div>
		</td>
	</tr>
	<tr>
		<th style="width: 10%">Edit</th>
		<th>Trade Type</th>
		<th>Debit Account</th>
		<th>Credit Account</th>
		<th>Category</th>
	</tr>

	<?php foreach ($tradetypes as $tradetype): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $tradetype['TradeType']['id']));?></td>
			<td><?php echo $tradetype['TradeType']['trade_type']; ?></td>
			<td><?php echo $tradetype['Debit']['account_name']; ?></td>
			<td><?php echo $tradetype['Credit']['account_name']; ?></td>
			<td><?php echo $tradetype['TradeType']['category']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>
