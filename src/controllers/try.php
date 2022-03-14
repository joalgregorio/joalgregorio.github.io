<?php
require_once('dbParking.php');

$add_parking_slot = add_parking_slot('LP3','3,1,6', 2 );
if (!$add_parking_lot) {
    die('Error adding parking slot: ' . mysql_error());
}
echo 'Connected successfully';

?>
