<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php $page_title="Logout"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>

<?php
	$_SESSION = array();
	redirect_to("../login.php");
?>

<?php include("../includes/footer.php"); ?>
