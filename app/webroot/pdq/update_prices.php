<?php
/**
 *	Populate the pdq_updates table with prices from the pdq_prices table (prices from providers)
 **/
$limit = 50;	//limit to number of stocks processed at once

//First get list of prices from pdq_prices table
$mysqli = new mysqli('asapdb01.cqezga1cxvxz.us-east-1.rds.amazonaws.com', 'asapuser', 'templ88', 'ASAPDB01');
$query = "SELECT sec_id, provider_id, price, price_date FROM pdq_prices ORDER BY CRD ASC LIMIT $limit";
$result = $mysqli->query($query);

//Check to see if there are any rows to process, if not exit
if ($result->num_rows == 0) {
	echo "No prices to retrieve";
	exit;
}

while($secRow = $result->fetch_array(MYSQLI_ASSOC)) {
	$sec = $secRow['sec_id'];
	$provider = $secRow['provider_id'];
	$price = $secRow['price'];
	$date = $secRow['price_date'];
	
	//find if a row already exists for this security
	$rec = $mysqli->query("SELECT * FROM pdq_updates WHERE sec_id='".$sec."'");
	if ($rec->num_rows > 0) {
		$recrow = $rec->fetch_array(MYSQLI_ASSOC);
		$upd_id = $recrow['id'];
		$upd_provider = $recrow['provider_id'];
		$upd_price = $recrow['price'];
		$upd_yahoo_price = $recrow['yahoo_price'];
		$upd_yahoo_date = $recrow['yahoo_date'];
		$upd_google_price = $recrow['google_price'];
		$upd_google_date = $recrow['google_date'];
		$upd_bloomberg_price = $recrow['bloomberg_price'];
		$upd_bloomberg_date = $recrow['bloomberg_date'];
	}
	else {
		//create a new row, first get the provider_id from the secs table
		// **N.B. The provider_id column in tables pdq_prices and pdq_updates refer to different entities**
		$secquery = $mysqli->query("SELECT provider_id FROM secs where id='".$sec."'");
		
		if ($secquery->num_rows == 0) {
			echo "Failure to access secs table";
			exit;
		}
		else {
			$secdata = $secquery->fetch_array(MYSQLI_ASSOC);
			$upd_provider = $secdata['provider_id'];
			//Insert row into pdq_updates
			if (!$mysqli->query("INSERT INTO pdq_updates (sec_id, provider_id) VALUES ('$sec','$upd_provider')")) {
				echo "Could not insert row into pdq_updates";
				exit();
			}
			else {
				$upd_id = $mysqli->insert_id;
				$upd_price = null;
				$upd_yahoo_price = null;
				$upd_yahoo_date = null;
				$upd_google_price = null;
				$upd_google_date = null;
				$upd_bloomberg_price = null;
				$upd_bloomberg_date = null;
			}
		}
		$secquery->free();
	}
	$rec->free();
	
	//prepare new update values
	switch ($provider) {
		case 2:
			//yahoo
			$upd_yahoo_price = $price;
			$upd_yahoo_date = $date;
			$upd_price_date = $date;
			break;
			
		case 3:
			//google
			$upd_google_price = $price;
			$upd_google_date = $date;
			$upd_price_date = $date;
			break;
			
		case 4:
			//bloomberg.com
			$upd_bloomberg_price = $price;
			$upd_bloomberg_date = $date;
			$upd_price_date = $date;
			break;
	}
	
	//calculate new price, working out median/average if necessary
	$old_price = $upd_price;
	switch ($upd_provider) {
		case 0:
			//median
			$upd_price = median($upd_yahoo_price, $upd_google_price, $upd_bloomberg_price);
			break;
		
		case 1:
			//average
			$upd_price = average($upd_yahoo_price, $upd_google_price, $upd_bloomberg_price);
			break;
		
		case 2:
			//yahoo
			$upd_price = $upd_yahoo_price;
			break;
		
		case 3:
			//google
			$upd_price = $upd_google_price;
			break;
			
		case 4:
			//bloomberg.com
			$upd_price = $upd_bloomberg_price;
			break;
	}
	
	//write row with updated numbers back into pdq_updates
	$query = "UPDATE pdq_updates SET price=$upd_price, price_date='$upd_price_date' ";
	if (isset($upd_yahoo_price) && isset($upd_yahoo_date)) {
		$query .= ", yahoo_price=$upd_yahoo_price, yahoo_date='$upd_yahoo_date' ";
	}
	if (isset($upd_google_price) && isset($upd_google_date)) {
		$query .= ", google_price=$upd_google_price, google_date='$upd_google_date' ";
	}
	if (isset($upd_bloomberg_price) && isset($upd_bloomberg_date)) {
		$query .= ", bloomberg_price=$upd_bloomberg_price, bloomberg_date='$upd_bloomberg_date' ";
	}
	$query .= "WHERE id=$upd_id";
	$mysqli->query($query);
	
	//delete the price row from pdq_prices so that it won't be processed again
	$mysqli->query("DELETE FROM pdq_prices ORDER BY crd ASC LIMIT 1");
}
 
$result->free();
$mysqli->close();


///////////////// Maths functions from http://php.net/manual/en/ref.math.php //////////////////
function median()
{
    $args = func_get_args();

    switch(func_num_args())
    {
        case 0:
            trigger_error('median() requires at least one parameter',E_USER_WARNING);
            return false;
            break;

        case 1:
            $args = array_pop($args);
            // fallthrough

        default:
            if(!is_array($args)) {
                trigger_error('median() requires a list of numbers to operate on or an array of numbers',E_USER_NOTICE);
                return false;
            }

			// remove any NULLs
			foreach ($args as $key=>&$value) {
				if (!isset($value)) {
					unset($args[$key]);
				}
			}
			
            sort($args);
           
            $n = count($args);
            $h = intval($n / 2);

            if($n % 2 == 0) {
                $median = ($args[$h] + $args[$h-1]) / 2;
            } else {
                $median = $args[$h];
            }

            break;
    }
   
    return $median;
}


function average($arr)
{
   if (!is_array($arr)) return false;

	// remove any NULLs
	foreach ($arr as $key=>&$value) {
		if (!isset($value)) {
			unset($arr[$key]);
		}
	}
   return array_sum($arr)/count($arr);
}
?>