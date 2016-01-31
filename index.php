<!doctype html>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./stylesheets/main.css">
	<title>OpenCards</title>
</head>

<body>
	<div class="container">
		<div class = "header"><h1>OpenCards</h1></div>
		<?php
		require 'databaseInit.php';
		
		echo isset($_GET["DECK"]) ? "Set" : "Unset";
		echo '<br />';
		echo "Deck get request: " . htmlspecialchars($_GET["deck"]);
		$db.close();
		?>
		Okay
	</div>
</body>

</html>
