<?php

require_once('controllers/parkingLogs.php');

date_default_timezone_set("Asia/Manila");

$parked = get_all_parked();

if($parked) {
	foreach($parked as $vehicle) {
		echo "<option value='" . $vehicle['plate_number'] . "'>" . $vehicle['plate_number']. "</option>";
	}
}

?>
