<?php
require_once("dbParking.php");

function get_avail_parking_slot($vehicle_type, $gate) {

 $sql_str = "SELECT name, id, substring_index(substring_index(gate_distance,','," . ($gate+1) . "),',',-1) AS gate_distance
             FROM parking_slots
             WHERE slot_type >= " . $vehicle_type .
                 " AND id NOT IN (SELECT DISTINCT parking_slot
                                    FROM parking_logs
                                    WHERE time_in IS NOT NULL
                                          AND time_out IS NULL)
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
