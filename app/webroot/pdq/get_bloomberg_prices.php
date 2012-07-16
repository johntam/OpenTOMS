<?php
/**
 *	Scrape prices from Bloomberg website
 **/
$limit = 10;	//limit to number of stocks processed at once

//First get stock list from pdq_actives
$mysqli = new mysqli('asapdb01.cqezga1cxvxz.us-east-1.rds.amazonaws.com', 'asapuser', 'templ88', 'ASAPDB01');
$query = "SELECT id, sec_id, sec_name, sec_type, ticker FROM pdq_actives 
			WHERE bloomberg_done=0 AND ((ticker<>'' AND sec_type=50) OR sec_type=1)
			ORDER BY id DESC LIMIT $limit";
$result = $mysqli->query($query, MYSQLI_STORE_RESULT);

//Check to see if there are any undone rows for this results cycle. If not then reset google_done flag to 0
//for all rows and rerun the query
if ($result->num_rows == 0) {
	$mysqli->query("UPDATE pdq_actives SET bloomberg_done=0");
	$result = $mysqli->query($query, MYSQLI_STORE_RESULT);
}

$idList = "";	//sequence of row ids used for updating the google_done flag to 1
$values = "";	//form list of values to be used in the INSERT query below

while($secRow = $result->fetch_array(MYSQLI_ASSOC)) {
	//Currency URL queries are different
	if ($secRow['sec_type'] == 1) {
		$url = "http://www.bloomberg.com/quote/USD".$secRow['sec_name'].":CUR";
	}
	else {
		$ticker = preg_replace('/^([0-9a-zA-Z]+)(\.| )*([0-9a-zA-Z]+)$/', '${1}:${3}', $secRow['ticker']);		
		$url = "http://www.bloomberg.com/quote/".$ticker."/profile";
	}
	
	$quote = file_get_contents($url,NULL,NULL,NULL,25000);
	preg_match('/^(.*)<span class=" price">(.*?)<span(.*)fine_print(.*?)As of(.*)ET on(.*?)([0-9]{2})\/([0-9]{2})\/([0-9]+)/s', $quote, $match);
	
	if (isset($match[1])) {
		$price = trim(str_replace(",","",$match[2]));
		$time = trim($match[5]);
		$month = trim($match[7]);
		$day = trim($match[8]);
		$year = trim($match[9]);
		$dateString = $year."-".$month."-".$day." ".$time;
		$dateT = new DateTime($dateString, new DateTimeZone('America/New_York'));
		$dateT->setTimezone(new DateTimeZone('Europe/London'));
		$dateString = $dateT->format("Y-m-d H:i:s");
		$values .= "(".$secRow['sec_id'].",4,".$price.",'".$dateString."'),";	//'4' is bloomberg provider id
		
		//console output
		echo $secRow['sec_name'].": ".$price."  ,  ".$dateString."</BR>";
	}
	
	$idList .= $secRow['id'].",";
}

$values = rtrim($values, ",");
$idList = rtrim($idList, ",");

//Insert row into pdq_prices
if ($values) {
	if (!$mysqli->query("INSERT INTO pdq_prices (sec_id, provider_id, price, price_date) VALUES $values;")) {
		echo "Could not insert row into pdq_prices";
		exit();
	}
}
 
// set the google_done flag against the original list of securities fetched
if (!$mysqli->query("UPDATE pdq_actives SET bloomberg_done=1 WHERE id IN (".$idList.")")) {
	echo "Could not mark bloomberg done flag in pdq_actives table";
	exit();
}
 
$result->free();
$mysqli->close();
?>