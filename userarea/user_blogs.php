<?php require_once("../server/sessions.php"); ?>
<?php require("../server/blog_functions.php");?>
<?php require("../server/user_functions.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php

//blog author
$friend_userid = $_POST['user'];

$friend_full_name = find_full_name($friend_userid);
$first_name= $friend_full_name['FirstName'];
$last_name = $friend_full_name['LastName'];

$page_title="{$first_name} {$last_name}'s Blogs";


$name = "{$first_name} {$last_name}'s Blogs";
?>

<h2><?php echo $name ?></h2>
<!--Blogs-->
<?php
    $blog_results = find_blogs($friend_userid);
		while($blog_posts = mysqli_fetch_assoc($blog_results)) {

            $title = $blog_posts["Title"];

            $date_posted = $blog_posts["DatePosted"];
            $formatted_date  = display_formatted_date($date_posted);


            $output = "Title: <td><a href='user_blog.php?title={$title}&user={$friend_userid}'> {$title} </a></td><br />";
            $access_rights  = $blog_posts['AccessRights'];


            //hardcoded the two booleans for now; preliminary confirm access rights method
            $check = confirm_access_rights($access_rights, true, true);

            /*
             * Check will return true if the blog should be visible to the currently connected user.
             * Posts which are not visible (e.g. only me access) will not be rendered
             */
            if($check == true) {


                ?>

                <br>

                <div class="individual-blog">

                    <?php echo $output; ?>
                    <h6><?php echo $formatted_date ?></h6>

                </div>

                <?php } //closing if statement

}// closing while loop
    mysqli_free_result($blog_results);
?>	
<hr />
<a href="logout.php">Logout</a>
<?php include("../includes/footer.php"); ?>
