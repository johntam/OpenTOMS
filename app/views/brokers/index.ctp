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
			<h1>Brokers</h1>
			<?php echo $this->Html->link('Add New Broker', array('controller' => 'brokers', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>Broker Code</th>
		<th>Broker Long Name</th>
		<th>Standard Commission Rate</th>
		<th>Edit</th>
	</tr>

	<!-- Here is where we loop through our $brokers array, printing out broker info -->

	<?php foreach ($brokers as $broker): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $broker['Broker']['id']; ?></td>
		<td><?php echo $broker['Broker']['broker_name']; ?></td>
		<td><?php echo $broker['Broker']['broker_long_name']; ?></td>
		<td><?php echo $broker['Broker']['commission_rate']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $broker['Broker']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
