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
		<td>
		<h1>Funds</h1>
		<?php echo $this->Html->link('Add Fund', array('controller' => 'funds', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>Fund Name</th>
		<th>Fund Currency</th>
		<th>Management Fee</th>
		<th>Edit</th>
	</tr>

	<!-- Here is where we loop through our $funds array, printing out fund info -->

	<?php foreach ($funds as $fund): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $fund['Fund']['id']; ?></td>
		<td><?php echo $fund['Fund']['fund_name']; ?></td>
		<td><?php echo $fund['Currency']['currency_iso_code']; ?></td>
		<td><?php echo $fund['Fund']['management_fee']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $fund['Fund']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
