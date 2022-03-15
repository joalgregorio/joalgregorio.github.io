<?php
function db_connect()
{
	$db_con = mysql_connect("127.0.0.1",'root','M1lesT0n3@5!');
	mysql_select_db('ayala_parking',$db_con);
	return $db_con;
}

function db_query($sql_str)
{
	$db_conn = db_connect();
	$res = mysql_query($sql_str, $db_conn);

	if($res) {
		$data = array();
		while ($row = mysql_fetch_assoc($res)) {
			$data[] = $row;
		}
		mysql_free_result($res);

		return $data;
	} else {
		die(mysql_error() . "\n" . $sql_str . "\n");
	}
}



function db_insert($sql_str)
{
	$db_conn = db_connect();
        $res = mysql_query($sql_str, $db_conn);

	return $res;
}

function db_update($sql_str)
{
        $db_conn = db_connect();
        $res = mysql_query($sql_str, $db_conn);

        return $res;
}


function add_parking_slot($name, $gate_distance, $slot_type)
{

	$conn = db_connect();

	$sql_query = "INSERT INTO sql6478050.parking_slots (name, gate_distance, slot_type) VALUES('" . $name . "','" . $gate_distance . "'," . $slot_type . ")";

	$return = mysql_query($sql_query, $conn);

	if(!$return) {
		die('Error in adding new parking slot: '. mysql_error());
	}

	return $return;
}
