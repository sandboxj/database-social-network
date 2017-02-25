<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $page_title="Messages"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

		<h2>Your Messages</h2>
		<!--Insert code here-->
		
		<hr />
		<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
