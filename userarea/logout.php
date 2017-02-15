<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php $page_title="Logout"?>
<?php confirm_logged_in(); ?>
<?php
	$_SESSION = array();
	redirect_to("login.php");
?>

