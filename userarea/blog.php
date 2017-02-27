<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $page_title="{$_GET['title']}"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

		<h2><?php $title = "{$_GET['title']}"; echo $title ?></h2>
    <!--Blog-->
		<?php
    $query = "SELECT * from blog
              WHERE blog.UserID = '{$_SESSION["UserID"]}'
              AND blog.Title = '{$_GET['title']}'";
    $blog_results = mysqli_query($conn, $query);
    confirm_query($blog_results);
		while($blog = mysqli_fetch_assoc($blog_results)) {
        $datetime = explode(' ', $blog["DatePosted"], 2);
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
				$output .= $blog["Content"] . "<br />";
				echo $output . "<hr />";
        $comments = "SELECT * from blog_comment
                 WHERE blog_comment.BlogID = '{$blog["BlogID"]}'";
        $comments_results = mysqli_query($conn, $comments);
        confirm_query($comments_results);
		    while($comment = mysqli_fetch_assoc($comments_results)) {
            $commenter = "SELECT * from user
                          WHERE user.UserID = '{$comment["CommenterUserID"]}'";
            $user_results = mysqli_query($conn, $commenter);
            confirm_query($user_results);
            $user = mysqli_fetch_assoc($user_results);
				    $output2 = "<td><a href='user_profile.php?id={$user["UserID"]}'>" . $user["FirstName"] . " " . $user["LastName"] . "</a></td>";
				    $output2 .= " , " . $comment["DatePosted"] . "<br />";
				    $output2 .= $comment["Content"] . "<br />";
				    echo $output2;
		    }
    mysqli_free_result($blog_results);
    mysqli_free_result($comments_results);
		}
		?>
    <br/><br/><a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
