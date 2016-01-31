<!doctype html>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./stylesheets/main.css">
	<title>Opencards</title>
</head>
<body>
	<div class ="header"><h1>Opencards</h1></div>
	<div class="container">
		<?php
		require 'databaseInit.php';
		// $db
		$getExists = isset($_GET["deck"]);
		$get = $_GET["deck"];
		echo '<br />';
		echo "Deck get request: " . htmlspecialchars($_GET["deck"]);
		?>
		<script>
		var getExists = <?php echo json_encode($getExists); ?>;
		if (getExists) {
			alert("Exists!");
			// then get 
		} else {
			alert("ok");
			// get and write names
			$.post("ajaxReq.php", {req: "names"}).done(function(data) {
				var names = JSON.parse(data);
				for (var x = 0; x < names.length; x++) {
					$(".container").append("<p>" + (x+1) + ": " + names[x] + "</p>");
				}
			});
		}
		</script>
		<?php
		echo $getExists ? "Set" : "Unset";
		$db->close();
		?>
	</div>
	<div class="footer">Developed by Matthew Chung  |  matthew.chung@uwaterloo.ca</div>
</body>

</html>
