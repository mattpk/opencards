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
		$table[] = array($row['FRONT'], $row['BACK'], $row['ID']);
	}

	$reply = array($name,$table);
	echo json_encode($reply);
} else if ($req === "edit") {
	$flipped = $_POST['flipped'];
	$id = $_POST['id'];
	$tableName = "t_" . $id;
	$cardid = $_POST['cardid'];
	$text = $_POST['text'];

	$que = "SHOW TABLES LIKE '" . $tableName . "'";

	// check that it exists
	if (!$result = $db->query($que)) {
		echo json_encode("FAILED for $tableName to EXIST.");
		die("There was an error checking if $tableName exists");
	}

	$side = $flipped ? 'BACK' : 'FRONT';

	$reply = array($side, $id, $tableName, $cardid, $text, $side);
	echo json_encode($reply);


	/* create a prepared statement */
	if (($result->num_rows > 0) && ($stmt = $mysqli->prepare("UPDATE $tableName SET $side = ? WHERE ID = ?"))) {

	    /* bind parameters for markers */
	    $stmt->bind_param('ss', $text, $cardid);

	    /* execute query */
	    $stmt->execute();
	    
	    if ($stmt->errno) {
	      echo "FAILURE!!! " . $stmt->error;
	    }
	    else echo json_encode("Updated {$stmt->affected_rows} rows");


	    $stmt->close();
	}
	
} else if ($req === "new") {

} else {
	echo json_encode(array("Uhoh" , "Why", "We here?"));
}
?>