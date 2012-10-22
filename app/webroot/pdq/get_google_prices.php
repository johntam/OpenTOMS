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
 *	Scrape prices from Google Finance
 **/
$limit = 20;	//limit to number of stocks processed at once

//First get stock list from pdq_actives
$mysqli = new mysqli('asapdb01.cqezga1cxvxz.us-east-1.rds.amazonaws.com', 'asapuser', 'templ88', 'ASAPDB01');
$query = "SELECT id, sec_id, sec_name, sec_type, ric_code FROM pdq_actives 
			WHERE google_done=0 AND ((ric_code<>'' AND sec_type=50) OR sec_type=1)
			ORDER BY id DESC LIMIT $limit";
$result = $mysqli->query($query, MYSQLI_STORE_RESULT);

//Check to see if there are any undone rows for this results cycle. If not then reset google_done flag to 0
//for all rows and rerun the query
if ($result->num_rows == 0) {
	$mysqli->query("UPDATE pdq_actives SET google_done=0");
	$result = $mysqli->query($query, MYSQLI_STORE_RESULT);
}

$idList = "";	//sequence of row ids used for updating the google_done flag to 1
$values = "";	//form list of values to be used in the INSERT query below

while($secRow = $result->fetch_array(MYSQLI_ASSOC)) {
	//See if we are dealing with a currency here
	if ($secRow['sec_type'] == 1) {
		$url = "http://www.google.com/finance/converter?a=1&from=USD&to=".$secRow['sec_name'];
		$fxdata = file_get_contents($url);
		preg_match('/currency_converter_result.*?bld>(([0-9]|\.)*)/s', $fxdata, $match);
		$price = trim($match[1]);
		$dateT = new DateTime("now", new DateTimeZone('Europe/London'));
		$datestring = $dateT->format("Y-m-d H:i:s");
		
		$values .= "(".$secRow['sec_id'].",3,".$price.",'".$datestring."'),";	//'3' is google provider id
		//console output
		echo $secRow['sec_name'].": ".$price."  ,  ".$datestring."</BR>";
	}
	else {
		//non-currency
		$url = "http://www.google.com/ig/api?stock=".$secRow['ric_code'];
		$xmlstr = file_get_contents($url);
		$google = simplexml_load_string($xmlstr);
		$price = $google->xpath('//last');
		$price = $price[0]['data'];
		$date = $google->xpath('//trade_date_utc');
		$date = $date[0]['data'];
		$time =  $google->xpath('//trade_time_utc');
		$time = $time[0]['data'];
			
		if ((string)$price && (string)$date && (string)$time) {
			$datestring = substr($date, 0,4)."-".substr($date, 4,2)."-".substr($date,6,2)." ".substr($time,0,2).":".substr($time,2,2).":".substr($time,4,2);
			//convert Google times (UTC) to BST
			$dateT = new DateTime($datestring, new DateTimeZone('UTC'));
			$dateT->setTimezone(new DateTimeZone('Europe/London'));
			$datestring = $dateT->format("Y-m-d H:i:s");
			
			$values .= "(".$secRow['sec_id'].",3,".$price.",'".$datestring."'),";	//'3' is google provider id
			
			//console output
			echo $secRow['ric_code'].": ".$price."  ,  ".$datestring."</BR>";
		}
	}
	
	$idList .= $secRow['id'].",";
}

$values = rtrim($values, ",");
$idList = rtrim($idList, ",");

//Insert row into pdq_prices
if ($values) {
	if (!$mysqli->query("INSERT INTO pdq_prices (sec_id, provider_id, price, price_date) VALUES $values;")) {
		echo "Could not insert row into pdq_prices</BR>";
		echo $mysqli->error;
		exit();
	}
}
 
// set the google_done flag against the original list of securities fetched
if (!$mysqli->query("UPDATE pdq_actives SET google_done=1 WHERE id IN (".$idList.")")) {
	echo "Could not mark google done flag in pdq_actives table";
	exit();
}
 
$result->free();
$mysqli->close();
?>
