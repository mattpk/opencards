<?php
function console_log( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'php: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'php: " . $data . "' );</script>";

    echo $output;
}

// databaseInit.php connects to the DB, checks if the necessary base table exists, if not, creates it.
// remember to $db.close() later

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

// check that decks exists
if (!$result = $db->query("SHOW TABLES LIKE 'Decks'")) {
	die('There was an error checking if Decks exists');
}

$decksExists = $result->num_rows > 0;
if (!$decksExists) {
	$sql = "CREATE TABLE Decks (ID int AUTO_INCREMENT, NAME varchar(64), PRIMARY KEY(ID))";
	if (!$result = $db->query($sql)) {
		die('There was an error creating the Decks table');
	}
}

console_log("success init");
?>