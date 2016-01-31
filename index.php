<!doctype html>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./stylesheets/main.css">

	<title>Opencards</title>
</head>
<body>
	<div class ="header"><a href="index.php"><img style="display:block;    max-width: 100%;
    height: auto; margin-left:auto; margin-right: auto;" src="./img/logo.png"></img></a></div>
	<div class="container">

		<div id = "cardwrapper">
			<div id="card">
				<span id = "cardtext">
					Lorem ipsum dolor sit amet, nonummy ligula volutpat hac integer nonummy. Suspendisse ultricies, congue etiam tellus, erat libero, nulla eleifend, mauris pellentesque. Suspendisse integer praesent vel, integer gravida mauris, fringilla vehicula lacinia non
				</span>
			</div>
		</div>

		<?php
		require 'databaseInit.php';
		// $db
		$getExists = isset($_GET["deck"]);
		$get = $_GET["deck"];
		
		console_log("Deck get request: " . htmlspecialchars($_GET["deck"]));
		?>
		<script>var getExists = <?php echo json_encode($getExists); ?>;</script>
		<script src="cards.js"></script>
		<?php
		console_log($getExists ? "Set" : "Unset");
		$db->close();
		?>
	</div>
	<div class="footer">Developed by Matthew Chung  |  matthew.chung@uwaterloo.ca</div>
</body>

</html>
