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

// queries the database with an unparameterized query, does nothing with the output, dies with an error message if fails.
function simpleQuery($query, $errormsg) {
	if (!$result = $db->query($query)) {
		die($errormsg . ' [' . $db->error . ']');
	}
	$result->free();
}

// check that connection was successful
if($db->connect_errno > 0) {
	die('Unable to connect to database [' . $db->connect_error . ']');
}

// check that decks exists
if (!$result = $db->query("SHOW TABLES LIKE 'Decks'")) {
	die('There was an error checking if Decks exists');
}

// adds Decks table if doesn't exist
echo json_encode($result) . '<br />';
$decksExists = $result->num_rows > 0;
$result = $db->query("SHOW COLUMNS FROM 'Decks'");
echo "deck columns: " . json_encode($result) . '<br />';
while($row = $result->fetch_assoc()){
	echo "Ok";
    echo $row['FIELD'] . '<br />';
}
if (!$decksExists) {
	$sql = "CREATE TABLE Decks (ID int AUTO_INCREMENT, NAME varchar(64), PRIMARY KEY(ID))";
	simpleQuery($sql, 'There was an error creating the Decks table');
}

// checks that tutorialExists
if (!$result = $db->query("SHOW TABLES LIKE 't_1'")) {
	die('There was an error checking if t_1 exists');
}

//adds tutorial if doesn't exist
$t_1Rows = $result->num_rows;
if (!$result = $db->query("SELECT `NAME` from Decks")) {
	die('There was an error checking number of decks.');
}
$numDecks = $result->num_rows;

if ($t_1Rows == 0 && $t_1InDecks == 0) {
	/*
	echo ("1");
	simpleQuery("INSERT INTO Decks (NAME) VALUES ('OpenCards Tutorial')", "n");
	echo ("2");
	simpleQuery("CREATE TABLE t_1 (FRONT TINYTEXT, BACK TINYTEXT)", 'There was an error creating the tutorial table');
	echo ("3");
	simpleQuery("INSERT INTO t_1 VALUES ('Welcome to OpenCards. Click the card or the Flip button see the other side!', 'OpenCards is an open-source, lightweight flashcard app. Press the Next Button for the next card!')", "n");
	echo ("4");
	simpleQuery("INSERT INTO t_1 VALUES ('You can create your own decks of flashcards, and share them.', 'OpenCards is also very mobile-friendly, which is a huge plus for studying on the go!')", "n");
	echo ("5");
	simpleQuery("INSERT INTO t_1 VALUES ('Whats good my man', 'Not much, you?')", "n");
	echo ("6");
	simpleQuery("INSERT INTO t_1 VALUES ('Whats good my man', 'Not much, you?')", "n");
	echo ("7");
	*/
}

console_log("t_1 rows: " . $t_1Rows);
console_log("num decks: " . $numDecks);
console_log("success init");
?>