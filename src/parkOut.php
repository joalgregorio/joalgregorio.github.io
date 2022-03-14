<?php

require_once('controllers/parkingSlots.php');

date_default_timezone_set("Asia/Manila");

$plate_num = $_GET['plate_number'];
$time_out = $_GET['time_out'];

$res = do_check_out($plate_num,  $time_out);

echo "<div class='col-md-12'>";
echo "<div class='alert ";
echo $res['status'] == 1 ? "alert-success" : "alert-danger";
echo  "' role='alert'>";
echo $res['status'] == 1 ? "Total Amount Due: <strong>" . $res['total_due'] . "</strong>" : $res['message'];
echo "</div></div>";


?>
