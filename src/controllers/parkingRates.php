<?php
require_once("dbParking.php");


function get_hourly_rate ($parking_slot){

	$sql_str = "SELECT rate_per_hour FROM parking_rates AS a INNER JOIN parking_slots AS b on a.slot_type = b.slot_type WHERE b.id = " . $parking_slot;
	$res = db_query($sql_str);
        if($res) {
                foreach($res as $rate) {
                        return $rate['rate_per_hour'];
                }
        } 
}

function get_total_charges ($parking_slot, $num_hours = 0) {
	
	$rate = get_hourly_rate($parking_slot);
	
	return $num_hours <= 3 ? 40 : ($num_hours % 24) * $rate + (($num_hours - ($num_hours % 24)) / 24) * 5000;
}
