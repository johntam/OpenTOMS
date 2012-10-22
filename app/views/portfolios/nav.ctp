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

<table style="width: 30%;margin-left:35%;margin-right:35%;">
	<tr><td colspan="15"><h4>NAV Report</h4></td></tr>
	<tr>
		<th>Security Name</th>
		<th>Quantity</th>
		<th>CCY</th>
		<th>Price</th>
		<th>Market Val (Local)</th>
		<th>Market Val (Fund)</th>
		<th>Errors</th>
	</tr>

	<?php foreach ($portfolio_data as $data): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $data['sec_name']; ?></td>
		<td><?php echo $data['position']; ?></td>
		<td><?php echo $data['currency']; ?></td>
		<td><?php echo $data['price']; ?></td>
		<td><?php echo $data['mkt_val_local']; ?></td>
		<td><?php echo $data['mkt_val_fund']; ?></td>
		<td><?php if (isset($data['message'])) {echo $data['message'];} ?></td>
	</tr>
	<?php endforeach; ?>
</table>
