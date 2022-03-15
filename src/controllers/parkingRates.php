<?php
require_once("dbParking.php");


function get_total_charges ($parking_slot, $time_out) {

	$time_out = $time_out ? $time_out : date("Y-m-d H:i:s");
	$sql_str = "SELECT CEILING(TIMESTAMPDIFF(SECOND, time_in,'" . $time_out .  "')/3600) as total_hours, c.rate_per_hour, a.prev_paid
						  FROM parking_logs as a JOIN parking_slots as b
							ON a.parking_slot = b.id JOIN parking_rates as c
							ON b.slot_type = c.slot_type
							WHERE time_out IS NULL and b.id = ". $parking_slot ;
  $res = db_query($sql_str);
	$hours_and_rate = $res[0];

	$total_due = 0;
	$num_hours = $hours_and_rate['total_hours'];
	$rate = $hours_and_rate['rate_per_hour'];
	$prev_paid = $hours_and_rate['prev_paid'];
	if($num_hours <= 3) {
		$total_due = 40 - $prev_paid;
	} else if($num_hours < 24) {
		$total_due = (($num_hours - 3) * $rate) + 40 - $prev_paid;
	} else {
		$total_due = ($num_hours % 24) * $rate + ((($num_hours - ($num_hours % 24)) / 24) * 5000) - $prev_paid;
	}

  $totals['total_due'] = $total_due ? $total_due : 0;
	$totals['total_hours'] = $num_hours;

	return $totals ? $totals : false;
}
