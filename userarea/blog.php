<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/validation_functions.php");?>
<?php require_once("../server/validation_blog.php");?>
<?php $page_title="Blogs"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

		<h2>Your Blogs</h2>
		<p>Nice to see you again, <?php echo htmlentities($_SESSION["FirstName"]);?> !</p>
		<?php echo message()?>
		<form action="blog.php" method="post">
			<textarea style="width: 80%" name="blog_content"></textarea><br />
			<input type="submit" name="blog_post" value="Post" />
		</form>
		<hr />

		<!--Blogs below-->
		<?php
			$userid = $_SESSION["UserID"];
			$blog_results = find_blogs($userid);
			while($blog_posts = mysqli_fetch_assoc($blog_results)) {
		?>
			<div>
				<?php 
					$output = "Author: " . $blog_posts["UserID"];
					$output .= " , " . $blog_posts["DatePosted"] . "<br />";
					$output .= $blog_posts["Content"] . "<br />";
					echo $output;
				?>
		<?php
			}
		?>
		<?php
			mysqli_free_result($blog_results);
		?>
		<!--<img src="img/greatsuccess.jpg">-->
		<hr />
		<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
