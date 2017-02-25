<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_search.php");?>
<?php $page_title="Search"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<h2>Search</h2>
<?php echo message()?>

<form action="search.php" method="post">
    <textarea rows="1" style="width: 20%" name="search_query"></textarea><br />
    <input type="submit" name="search_result" value="Search" />
</form>

<hr />
<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
