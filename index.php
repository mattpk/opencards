<!doctype html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./stylesheets/main.css">
	<title>OpenCards</title>
</head>

<body>
	<div class="container">
		<div class = "header"><h1>OpenCards</h1></div>
		<?php
		echo 'before';
		include 'databaseInit.php';
		echo(htmlspecialchars($_GET["deck"]));
		$db.close();
		?>
		Okay
	</div>
</body>

</html>
