<?php
/*
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
*/	

$mysqli = new mysqli("asapdb01.cqezga1cxvxz.us-east-1.rds.amazonaws.com","asapuser","templ88","ASAPDB01");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$id = $this->params['pass']['0'];
$query = "SELECT name, size, content, type FROM attachments WHERE id=".$id;
$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_ASSOC);
$data = base64_decode($row['content']);
$name = $row['name'];
$size = $row['size'];
$type = $row['type'];

/* free result set */
$result->free();

/* close connection */
$mysqli->close();

header("Content-type: $type");
header("Content-length: $size");
header("Content-Disposition: attachment; filename=$name");
header("Content-Description: PHP Generated Data");
echo $data;
