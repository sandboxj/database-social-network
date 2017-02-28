<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require("../server/blog_functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_blog.php");?>
<?php $page_title="{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Blogs"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<h2>Your Blogs</h2>

<?php echo message()?>

<form action="blogs.php" method="post">
    <h4>Title</h4><textarea rows="1" style="width: 20%" name="blog_title"></textarea><br />
    <h4>Content</h4><textarea rows="8" style="width: 60%" name="blog_content"></textarea><br />

    <select name ="access">
        <option value="me">Only me</option>
        <option selected="friends">Friends</option>
        <option value="everybody">Everybody</option>
        <option value="circles">Circles</option>

    </select>
		<input type="submit" name="blog_post" value="Post" />
</form>

<hr />



<!--Blogs below-->
<?php
    $blog_results = find_blogs($_SESSION["UserID"]);
		while($blog_posts = mysqli_fetch_assoc($blog_results)) {
        $output = "Title: <td><a href='blog.php?title={$blog_posts["Title"]}'>" . $blog_posts["Title"] . "</a></td><br />";
		    $datetime = explode(' ', $blog_posts["DatePosted"], 2);
        $date = explode('-', $datetime[0], 3);
        $time = explode(':', $datetime[1], 3);
        if ($date[2] == 1) {
            $date[2] = "1st";
        } elseif ($date[2] == 2) {
            $date[2] = "2nd";
        } elseif ($date[2] == 3) {
            $date[2] = "3rd";
        } else {
            $date[2] = "{$date[2]}th";
        }
        if ($date[1] == 1) {
            $date[1] = "Jan";
        } elseif ($date[1] == 2) {
            $date[1] = "Feb";
        } elseif ($date[1] == 3) {
            $date[1] = "Mar";
        } elseif ($date[1] == 4) {
            $date[1] = "Apr";
        } elseif ($date[1] == 5) {
            $date[1] = "May";
        } elseif ($date[1] == 6) {
            $date[1] = "Jun";
        } elseif ($date[1] == 7) {
            $date[1] = "Jul";
        } elseif ($date[1] == 8) {
            $date[1] = "Aug";
        } elseif ($date[1] == 9) {
            $date[1] = "Sep";
        } elseif ($date[1] == 10) {
            $date[1] = "Oct";
        } elseif ($date[1] == 11) {
            $date[1] = "Nov";
        } else {
            $date[1] = "Dec";
        }
        if ($time[0] > 12 || $time[0] == 00) {
            $time[0] = $time[0] - 12;
            $suffix = " p.m.";
        } else {
            $suffix = " a.m.";
        }
				$output .= $date[2] . " " . $date[1] . " at " . $time[0] . ":" . $time[1] . $suffix . "<br />";
				echo $output;
        echo "<hr/>";
    }
    mysqli_free_result($blog_results);
?>

<a href="logout.php">Logout</a>
<?php include("../includes/footer.php"); ?>
