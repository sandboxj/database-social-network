 <?php
    require_once("server/redirect.php");
 ?>
 <?php 
    require_once("server/db_connection.php");
 ?>
 <?php   
    require_once("server/validations.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
	<head>
		<title>First Page</title>
	</head>
	<body>
		
		<?php 
            echo $message;
        ?><br />
		<form action="register_form.php" method="post">
			<!--Escape special chars on userinput-->
			Username: <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" /><br />
			Password: <input type="password" name="password" value="" /><br />
			First name: <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" /><br />
			Last name: <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" /><br />
			<br />
			<input type="submit" name="register" value="Register" />
		</form>
		<br />
		
		<?php $link_page = "second_page.php"; ?>
        <?php $link_text = rawurldecode($link_page); ?>
        <?php $id = 2; ?>
        <?php $user = "sandbox&"; ?>
        <a href="<?php echo htmlspecialchars($link_text); ?>?id=<?php echo $id; ?>&user=<?php echo urlencode($user); ?>">Second Page</a>

    </body>
</html>
