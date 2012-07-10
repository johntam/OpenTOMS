<?php
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

// get all active securities in the secs table
$query = "SELECT id, provider_id, ticker, ric_code, isin_code FROM secs WHERE act=1";
$result = $mysqli->query($query, MYSQLI_STORE_RESULT);

// process one row at a time from first query
while($secRow = $result->fetch_array(MYSQLI_ASSOC)) {
	$ticker = $secRow['ticker'];
	$ric_code = $secRow['ric_code'];
	$isin_code = $secRow['isin_code'];
	$id = $secRow['id'];
	$provider = $secRow['provider_id'];
	
	//Insert row into pdq_actives
	if (!$mysqli->query("INSERT INTO pdq_actives (sec_id, provider_id, ticker, ric_code, yahoo_done) VALUES ('$id', '$provider', '$ticker', '$ric_code', 0)")) {
		echo "Could not insert row into pdq_actives";
		exit();
	}
}

echo "pdq_actives table populated from secs table";

$result->free();
$mysqli->close();
?>