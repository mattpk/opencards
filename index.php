<!doctype html>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./stylesheets/main.css">
	<title>Opencards</title>
</head>
<body>
	<div class ="header"><a href="index.php"><img style="display:block; margin-left:auto; margin-right: auto;" src="./img/logo.png"></img></a></div>
	<div class="container">
		<?php
		require 'databaseInit.php';
		// $db
		$getExists = isset($_GET["deck"]);
		$get = $_GET["deck"];
		
		console_log("Deck get request: " . htmlspecialchars($_GET["deck"]));
		?>
		<script>
		var getExists = <?php echo json_encode($getExists); ?>;
		if (getExists) {
			$(".container").append("<div id='cardwrapper'><div id='card'></div></div>");
			$("#card").append("<div id ='cardtext'></div");
			$('#cardtext').text("You can do it. Finish this by tonight!");
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
		console_log($getExists ? "Set" : "Unset");
		$db->close();
		?>
	</div>
	<div class="footer">Developed by Matthew Chung  |  matthew.chung@uwaterloo.ca</div>
</body>

</html>
