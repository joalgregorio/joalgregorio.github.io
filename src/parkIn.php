<?php

require_once('controllers/parkingController.php');

date_default_timezone_set("Asia/Manila");

$plate_num = $_GET['plate_number'];
$vehicle_type = $_GET['vehicle_type'];
$gate = $_GET['gate'];
$time_in = $_GET['time_in'];


$res = do_check_in($vehicle_type, $plate_num, $gate, $time_in);

echo "<div class='col-md-12'>";
echo "<div class='alert ";
echo $res['status'] == 1 ? "alert-success" : "alert-danger";
echo  "' role='alert'>";
echo $res['message'];
echo "</div></div>";

?>
