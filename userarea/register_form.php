 <?php require_once("../server/sessions.php");?>
 <?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
 <?php require_once("../server/validation_functions.php"); ?>
 <?php require_once("../server/validations_register.php");?>
<?php $page_title="Registration"?>
<?php include("../includes/header.php"); ?>
		<h2>Registration</h2>
		
		<?php 
			echo message();
            echo $message;
        ?><br />
		<form action="register_form.php" method="post">
			<!--Escape special chars on userinput-->
			Username: <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" /><br />
			Password: <input type="password" name="password" value="" /><br />
			E-Mail: <input type="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" /><br />
			First name: <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" /><br />
			Last name: <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" /><br />
			Date of Birth: <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" /><br />
			Gender: <input type="radio" name="gender" value="male" />Male<input type="radio" name="gender" value="female" checked />Female<br />
			<br />
			<input type="submit" name="register" value="Register" />
		</form>
		<br />
        <a href="login.php">Back</a>

<?php include("../includes/footer.php"); ?>
