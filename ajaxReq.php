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


if ($req == "names") {
	if (!$result = $db->query("SELECT * FROM `decks`")) {
		die('Unable to load deck list. [' . $db->connect_error . ']');
	}
	while ($row = $result->fetch_assoc()) {
		$reply[] = array($row['ID'], $row['NAME']);
	}	
	echo json_encode($reply);
} elseif ($req == "deck") {

	$id = $db->real_escape_string($_POST['id']);
	$tableName = "t_" . $id;

	if (!$result = $db->query("SELECT `NAME` FROM `decks` WHERE `ID` = $id")) {
		die('Unable to find deck in db. [' . $db->connect_error . ']');
	}
	$row = $result->fetch_assoc();
	$reply[] = array($row['NAME'],array());

	/* create a prepared statement */
	if ($stmt = $db->prepare("SELECT * FROM ?")) {

		/* bind parameters for markers */
		$stmt->bind_param("s", $tableName);
		/* execute query */
		$stmt->execute();
		/* bind result variables */
		$stmt->bind_result($front, $back);
		/* fetch values */
		while($stmt->fetch()) {
			$reply[1][] = array($front, $back);
		}
		/* close statement */
		$stmt->close();
	}
	echo json_encode($reply);
} else {
	echo json_encode(array("Uhoh" , "Why", "We here?"));
}
?>