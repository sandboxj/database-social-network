<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php $page_title= "Admin Main Menu"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>

<form action="admin.php" method="post">
    <input type="submit" name="access" value="Full website access" />
</form>
<br/>
<form action="populate.php" method="post">
    <input type="submit" name="populate" value="Populate the database" />
</form>
<br/>

<hr />
<a href="logout.php">Logout</a>
<?php include("../includes/footer.php"); ?>