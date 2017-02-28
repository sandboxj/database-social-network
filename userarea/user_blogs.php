<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $query = "SELECT * FROM user u
                WHERE u.UserID like '{$_POST['user']}'";
                $displayed_user = mysqli_query($conn, $query);
                confirm_query($displayed_user);
                $user = mysqli_fetch_assoc($displayed_user) ?>
<?php $page_title="{$user["FirstName"]} {$user["LastName"]}'s Blogs"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<h2><?php $name = "{$user["FirstName"]} {$user["LastName"]}'s Blogs";
		  echo $name ?></h2>
<!--Blog-->
<?php
    $blog_results = find_blogs($user["UserID"]);
		while($blog_posts = mysqli_fetch_assoc($blog_results)) {
				$output = "Title: <td><a href='user_blog.php?title={$blog_posts["Title"]}&user={$_POST['user']}'>" . $blog_posts["Title"] . "</a></td><br />";
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
		}
    mysqli_free_result($blog_results);
?>	
<hr />
<a href="logout.php">Logout</a>
<?php include("../includes/footer.php"); ?>
