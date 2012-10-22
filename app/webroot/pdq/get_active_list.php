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

/**
 *	Get the latest active securities from the secs table along with identifier codes
 **/
 
$mysqli = new mysqli('asapdb01.cqezga1cxvxz.us-east-1.rds.amazonaws.com', 'asapuser', 'templ88', 'ASAPDB01');

// clear pdq_actives table
if (!$mysqli->query("DELETE FROM pdq_actives")) {
	echo "Failed to clear pdq_actives table";
	exit();
}
else {
	echo "pdq_actives table cleared</BR>";
}

// clear pdq_updates table
if (!$mysqli->query("DELETE FROM pdq_updates")) {
	echo "Failed to clear pdq_updates table";
	exit();
}
else {
	echo "pdq_updates table cleared</BR>";
}

// get all active securities in the secs table
$query = "SELECT S.id, S.sec_name, ST.sec_type, S.provider_id, S.ticker, S.ric_code, S.isin_code FROM secs S 
			INNER JOIN sec_types ST ON S.sec_type_id=ST.id WHERE S.act=1";
$result = $mysqli->query($query, MYSQLI_STORE_RESULT);

// process one row at a time from first query
while($secRow = $result->fetch_array(MYSQLI_ASSOC)) {
	$ticker = $secRow['ticker'];
	$ric_code = $secRow['ric_code'];
	$isin_code = $secRow['isin_code'];
	$id = $secRow['id'];
	$secname = $secRow['sec_name'];
	$sectype = $secRow['sec_type'];
	$provider = $secRow['provider_id'];
	
	//Insert row into pdq_actives
	if (!$mysqli->query("INSERT INTO pdq_actives (sec_id, sec_name, sec_type, provider_id, ticker, ric_code, yahoo_done) 
							VALUES ('$id', '$secname', '$sectype', '$provider', '$ticker', '$ric_code', 0)")) {
		echo "Could not insert row into pdq_actives";
		exit();
	}
}

echo "pdq_actives and pdq_updates tables populated from secs table";

$result->free();
$mysqli->close();
?>
