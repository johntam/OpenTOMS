<?php 
    // File: /app/views/trades/export.ctp 
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=file.csv");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$outstream = fopen("php://output", 'w');

	function __outputCSV(&$vals, $key, $filehandler) {
		fputcsv($filehandler, $vals, ',', '"');
	}
	array_walk($data, '__outputCSV', $outstream);

	fclose($outstream);
?>