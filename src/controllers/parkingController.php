<?php
include("dbParking.php");
include("parkingRates.php");
include("parkingLogs.php");
include("parkingSlots.php");
date_default_timezone_set("Asia/Manila");

function do_check_in ($vehicle_type, $plate_num, $gate, $time_in = null) {

	if(is_parked($plate_num)) {
		$return['status'] = 0;
		$return['message'] = "Vehicle is already parked.";
		return $return;
	}

	$parking_slot = get_avail_parking_slot($vehicle_type, $gate);

	if($parking_slot) {
		$time_in = $time_in ? $time_in : date("Y-m-d H:i:s");
		$prior_booking = get_prior_booking_last_60_min($plate_num,$time_in);
		$check_in_time = $prior_booking['time_in'] ? $prior_booking['time_in'] : $time_in;
		$prev_paid = $prior_booking['paid'] ? $prior_booking['paid'] : 0;
		log_park_in($parking_slot['id'], $vehicle_type, $plate_num, $check_in_time, $prev_paid);

		$return['slot'] = $parking_slot['name'];
		$return['status'] = 1;
		$return['message'] = "Vehicle Assigned To: <strong>" . $parking_slot['name'] . "</strong>" ;
	} else {
		$return['slot'] = null;
    $return['status'] = 0;
    $return['message'] = "No Available Parking Slot Found";
	}

	return $return;

}

function do_check_out ($plate_num, $time_out) {

	$park_in_details = is_parked($plate_num);
	if(!$park_in_details) {
		$return['status'] = 0;
    $return['message'] = "Vehicle is not parked.";
    return $return;
	}

	$time_out = $time_out ? $time_out : date("Y-m-d H:i:s");

  $totals = get_total_charges($park_in_details,$time_out);

	$res = log_park_out($plate_num, $time_out, $totals['total_due']);

	if($res) {
		$return['total_due'] = $totals['total_due'] . ' ( ' . $totals['total_hours'] . " ) hour/s";
                $return['status'] = 1;
                $return['message'] = "Vehicle " . $plate_num . " Parked Out";
	} else {
		$return['total_due'] = null;
                $return['status'] = 0;
                $return['message'] = "Error encountered when Parking Out";
	}

	return $return;

}
