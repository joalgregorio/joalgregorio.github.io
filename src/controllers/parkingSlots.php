<?php 
include("dbParking.php");
include("parkingRates.php");
date_default_timezone_set("Asia/Manila");

function do_check_in ($vehicle_type, $plate_num, $gate, $time_in = null) {

	
	if(is_parked($plate_num)) {
		$return['status'] = 0;
		$return['message'] = "Vehicle is already parked.";
		return $return;
	}
	
	$parking_slot = get_avail_parking_slot($vehicle_type, $gate);

	if($parking_slot) {
		$prior_booking_time = get_prior_booking_in_60_min($plate_num);
		$time_in = $time_in ? $time_in : date("Y-m-d H:i:s");
		$check_in_time = $prior_booking_time ? $prior_booking_time : $time_in;
		log_park_in($parking_slot['id'], $vehicle_type, $plate_num, $check_in_time);

		$return['slot'] = $parking_slot['name'];
		$return['status'] = 1;
		$return['message'] = "Parking slot found: " . $parking_slot['name'];
	} else {
		$return['slot'] = null;
                $return['status'] = 0;
                $return['message'] = "No Available Parking Slot Found";
	}

	return $return;

}

function log_park_in ($slot_id, $vehicle_type, $plate_num, $check_in_time) {

	$sql_str = "INSERT INTO parking_logs (parking_slot, plate_number, time_in) VALUES (" . $slot_id .  ",'" . $plate_num . "','" . $check_in_time . "')";
	$res = db_insert($sql_str);
	return $res ? true : null;

}

function is_parked ($plate_num) {
	$sql_str = "SELECT parking_slot FROM parking_logs WHERE time_out IS NULL AND plate_number = '" . $plate_num . "'";
	$res = db_query($sql_str);
	if($res) {
                foreach($res as $is_parked) {
                        return $is_parked['parking_slot'];
                }
        }

	return false;
}

function do_check_out ($plate_num, $time_out) {

	
	$park_in_details = is_parked($plate_num);
	if(!$park_in_details) {
		$return['status'] = 0;
                $return['message'] = "Vehicle is not parked.";
                return $return;
	}
	
	$time_out = $time_out ? $time_out : date("Y-m-d H:i:s");
		
	$prior_totals = get_total_hours($plate_num, $time_out);
	$total_charge = get_total_charges($park_in_details,$prior_totals['total_hours']);
	
	$total_due = $total_charge - $prior_totals['total_charges'];

	$res = log_park_out($plate_num, $time_out, $total_due);

	if($res) {
		$return['total_due'] = $total_due;
                $return['status'] = 1;
                $return['message'] = "Vehicle " . $plate_num . " Parked Out";
	} else {
		$return['total_due'] = null;
                $return['status'] = 0;
                $return['message'] = "Error encountered when Parking Out";
	}

	return $return;
}

function log_park_out ($plate_num, $time_out, $parking_due) {
	
	$sql_str = "UPDATE parking_logs SET time_out = '" . $time_out . "', charge = "  . $parking_due . " WHERE plate_number = '" . $plate_num. "' AND time_out is NULL ";
	$res = db_update($sql_str);
	return $res ? true : null;
}


function get_prior_booking_in_60_min ($plate_num)
{
	#Get last check out
	$sql_str = "SELECT MAX(time_out) as last_time_out FROM parking_logs WHERE plate_number = '" . $plate_num . "' AND TIMESTAMPDIFF(MINUTE, time_out, NOW()) < 60";
	
	$res = db_query($sql_str);
	if($res) {
		foreach($res as $time_out) {
			return $time_out['last_time_out'];
		}
	} 
	
	return false;
}

function get_total_hours ($plate_num, $time_out = null) {

	
	$time_out = $time_out ? $time_out : date("Y-m-d H:i:s");
	$sql_str = "SELECT CEILING(TIMESTAMPDIFF(MINUTE, MIN(time_in), '" . $time_out . "')/60) AS total_hours, SUM(charge) as total_charges 
			FROM ( SELECT DISTINCT b.parking_slot AS parking_slot, b.plate_number AS plate_number, b.time_in AS time_in, b.time_out AS time_out, b.charge  AS charge 
				FROM parking_logs AS a RIGHT JOIN parking_logs AS b 
				ON b.time_in = a.time_out OR b.time_out = a.time_in 
				WHERE b.plate_number = '" . $plate_num . "') as ab";
	$res = db_query($sql_str);
	if($res) {
                foreach($res as $total) {
                        return $total;
                }
        }

        return false;

}

function get_avail_parking_slot($vehicle_type, $gate) {

	#Who gets priority? The Slot Type or the distance of the vehicle from the gate?

	$sql_str = "SELECT name, id, substring_index(substring_index(gate_distance,','," . ($gate+1) . "),',',-1) as gate_distance 
		FROM parking_slots 
		WHERE slot_type >= " . $vehicle_type . " AND id NOT IN (SELECT DISTINCT parking_slot FROM parking_logs WHERE time_in IS NOT NULL AND time_out IS NULL) 
		ORDER BY gate_distance, slot_type 
		LIMIT 1";
	$res = db_query($sql_str);
	if($res) {
		foreach($res as $slot) {
			return $slot;
		}
	}
	return null;
}

function get_rates($parking_slot) {
	$sql_str = "SELECT * FROM parking_slots where id = " . $parking_slot;
	$res = db_query($sql_str);
        if($res) {
                foreach($res as $slot) {
                        return $slot;
                }
        }
	return false;
}

function get_all_parked() {
	$sql_str = "SELECT plate_number FROM parking_logs WHERE time_out IS NULL";
	$res = db_query($sql_str);
	return $res ? $res : false;
}
