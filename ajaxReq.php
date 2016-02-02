<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$name = substr($url["path"], 1);

$db = new mysqli($server, $username, $password, $name);

// check that connection was successful
if($db->connect_errno > 0) {
	die('Unable to connect to database [' . $db->connect_error . ']');
}

$req = htmlspecialchars($_POST['req']);


if ($req === "names") {
	if (!$result = $db->query("SELECT * FROM `decks`")) {
		die('Unable to load deck list. [' . $db->connect_error . ']');
	}
	while ($row = $result->fetch_assoc()) {
		$reply[] = array($row['ID'], $row['NAME']);
	}	
	echo json_encode($reply);
} elseif ($req === "deck") {

	$id = $db->real_escape_string($_POST['id']);
	$tableName = "t_" . $id;

	if (!$result = $db->query("SELECT `NAME` FROM `decks` WHERE `ID` = $id")) {
		die('Unable to find id in decks. [' . $db->connect_error . ']');
	}
	$row = $result->fetch_assoc();
	$name = $row['NAME'];

	if (!$result = $db->query("SELECT * FROM $tableName")) {
		die('Unable to fetch table. [' . $db->connect_error . ']');
	}
	while($row = $result->fetch_assoc()) {
		$table[] = array($row['ID'], $row['FRONT'], $row['BACK']);
	}

	$reply = array($name,$table);
	echo json_encode($reply);
} else if ($req === "edit") {

} else {
	echo json_encode(array("Uhoh" , "Why", "We here?"));
}
?>