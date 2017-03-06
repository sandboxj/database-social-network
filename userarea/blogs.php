<?php require("../server/blog_functions.php");?>
<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $page_title="{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Blogs"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php
//currently connected user
$userid = $_SESSION['UserID'];


/*
 * After pressing the post button. if statement handles validation
 * and insertion into the database.
 */
if(isset($_POST["blog_post"])){

    $blog_title =$_POST['blog_title'];
    $blog_content = $_POST['blog_content'];
    $access_rights=$_POST['access'];

    echo "access: ".$access_rights;

    $output = validate_blog_post($userid, $blog_title, $blog_content);

    if($output === ""){
        //post was successful
        insert_blog_post($userid, $blog_title, $blog_content, $access_rights);


    }else{
        $_SESSION['message']= $output;

    }

}

?>

<h2>Your Blogs</h2>

<?php echo message()?>



<hr />



<!--Blogs below-->
<div class="container-fluid" style="border-style: solid;" >
    <div class="row content">


        <div class="col-md-5">
            <br>

<?php
global $conn;

    $blog_results = find_blogs($_SESSION["UserID"]);
		while($blog_posts = mysqli_fetch_assoc($blog_results)) {

		//blog_title variable used below; using $title here for the output
        $title = $blog_posts["Title"];

        //escaping the title to put in db
        $escaped_title = mysqli_real_escape_string($conn,$blog_posts["Title"]);


        $date_posted = $blog_posts["DatePosted"];
        $formatted_date  = display_formatted_date($date_posted);

        $output = "Title: <td><a href='blog.php?title={$escaped_title}'> {$title} </a></td><br />";





        ?>

            <br>
<!--Inside WHILE, display every individual blog-->
            <div class="individual-blog">

                       <?php echo $output; ?>
                        <h6><?php echo $formatted_date?></h6>

            </div>




<?php

    }


    mysqli_free_result($blog_results);
?>

        </div>


<!--           Post area below     -->
        <div class="col-md-7 blog-post-area" style="background-color: #7EC0EE" >

                <form action="blogs.php" method="post">
                    <h4>Title</h4><textarea rows="1" style="width: 20%" name="blog_title"></textarea><br />
                    <h4>Content</h4><textarea rows="20" style="width: 100%" name="blog_content" contenteditable="true"></textarea><br />

                    <br>
                    <select name ="access">
                        <option value="0">Only me</option>
                        <option value="1" selected="1">Friends</option>
                        <option value="2">Everybody</option>
                        <option value="3">Circles</option>

                    </select>
                    <input type="submit" name="blog_post" value="Post" />

                </form>
            <br>
        </div>


    </div>
</div>


<a href="../logout.php">Logout</a>
<?php include("../includes/footer.php"); ?>
