<?php
require_once("dbParking.php");

function log_park_in ($slot_id, $vehicle_type, $plate_num, $check_in_time, $prev_paid = 0) {

        $sql_str = "INSERT INTO parking_logs (parking_slot, plate_number, time_in, prev_paid) VALUES (" . $slot_id .  ",'" . $plate_num . "','" . $check_in_time . "'," . $prev_paid .")";
        $res = db_insert($sql_str);

        return $res ? true : die("Error in adding parking log: " . mysql_error());

}

function log_park_out ($plate_num, $time_out, $parking_due) {

        $sql_str = "UPDATE parking_logs SET time_out = '" . $time_out . "', charge = "  . $parking_due . " WHERE plate_number = '" . $plate_num. "' AND time_out is NULL ";
        $res = db_update($sql_str);
        return $res ? true : die("Error in adding parking log: " . mysql_error());;
}

function is_parked ($plate_num) {

	$sql_str = "SELECT parking_slot
						  FROM parking_logs
							WHERE time_out IS NULL
							      AND plate_number = '" . $plate_num . "'";

	$res = db_query($sql_str);
	if($res) {
                foreach($res as $is_parked) {
                        return $is_parked['parking_slot'] ? true : false;
                }
        }

	return false;
}


function get_prior_booking_last_60_min ($plate_num, $time_in)
{

  $sql_str = "SELECT time_in, charge + prev_paid as paid
              FROM parking_logs
              WHERE plate_number = '" . $plate_num . "' AND TIMESTAMPDIFF(MINUTE, time_out, '" . $time_in . "') < 60";

	$res = db_query($sql_str);
	if($res) {
		foreach($res as $time_in) {
			return $time_in ? $time_in : false;
		}
	}

	return false;
}

function get_all_parked() {

	$sql_str = "SELECT plate_number
				      FROM parking_logs
							WHERE time_out IS NULL";
	$res = db_query($sql_str);
	return $res ? $res : false;

}
