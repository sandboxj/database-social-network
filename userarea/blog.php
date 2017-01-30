<?php require_once("../server/sessions.php"); ?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Blog</title>
	</head>
	<body>
		<h2>Your Blogs</h2>
		<p>Nice to see you again, <?php echo htmlentities($_SESSION["FirstName"]);?> !</p>		
		<img src="img/greatsuccess.jpg">
	</body>
</html>
