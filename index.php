<!doctype html>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./stylesheets/main.css">
	<title>Opencards</title>
</head>

<body>
	<div class="container">
		<div class = "header"><h1>Opencards</h1></div>
		<?php
		require 'databaseInit.php';
		// $db
		$getExists = $_GET["deck"];
		echo isset($getExists) ? "Set" : "Unset";
		echo '<br />';
		echo "Deck get request: " . htmlspecialchars($_GET["deck"]);
		?>
		<script>
		var getExists = <?php echo json_encode($getExists); ?>;
		</script>
		<?php
		$db.close();
		?>
		<script> console.log(getExists); </script>
		<h4> Made by Matthew Chung </h4>
	</div>
</body>

</html>
