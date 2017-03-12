<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../userarea/admin_populate/populate_db.php");?>
<?php $page_title= "Populate"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>


<form action="populate.php" method="post">
  <input type="submit" name="populate_users" value="Populate users!" />
  <input type="number" name="num_of_users" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_friendships" value="Populate friendships!" />
  <input type="number" name="num_of_friends" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_circles" value="Populate circles!" />
  <input type="number" name="num_of_circles" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_circle_members" value="Populate circle members!" />
  <input type="number" name="num_of_circle_members" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_collections" value="Populate collections!" />
  <input type="number" name="num_of_collections" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_photos" value="Populate photos!" />
  <input type="number" name="num_of_photos" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_photo_comments" value="Populate photo comments!" />
  <input type="number" name="num_of_photo_comments" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_blogs" value="Populate blogs!" />
  <input type="number" name="num_of_blogs" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_blog_comments" value="Populate blog comments!" />
  <input type="number" name="num_of_blog_comments" value="" />
</form>
<br/>
<form action="populate.php" method="post">
  <input type="submit" name="populate_messages" value="Populate messages!" />
  <input type="number" name="num_of_messages" value="" />
</form>
<br/>


<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
