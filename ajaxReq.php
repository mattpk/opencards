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

$reply = array(array(1,"First"));

if ($req = "names") {
	if (!$result = $db->query("SELECT * FROM `Decks`")) {
		die('Unable to load deck list. [' . $db->connect_error . ']');
	}
	while ($row = $result->fetch_assoc()) {
		$reply[] = array($row['ID'], $row['NAME']);
	}
	echo json_encode($reply);
} else {
	echo json_encode(array("Math" , "Science", "Test"));
}

$db.close();
?>