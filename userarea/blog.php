<?php require_once("../server/sessions.php"); ?>
<?php require("../server/blog_functions.php"); ?>
<?php require_once("../server/functions.php"); ?>
<?php require_once("../server/db_connection.php"); ?>
<?php require_once("../server/validation_blog.php"); ?>
<?php $page_title = "Blogs" ?>

<?php require_once("../server/db_connection.php");?>
<?php $page_title="{$_GET['title']}"?>

<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<!--HEADER HAS A OPENING BODY TAG and FOOTER has a closing one-->
<?php include("navbar.php"); ?>


<!--THIS PAGE IS FOR AN INDIVIDUAL BLOG POST-->
<?php

$userid = $_SESSION["UserID"];

$blog_title = "{$_GET['title']}";
$current_blogID = find_blog_id($userid, $blog_title);

$date_posted = find_blog_date($current_blogID);
$formatted_datetime = display_formatted_date($date_posted);

$blog_content = find_blog_content($current_blogID);
?>
<div class="container"><br>
</div>



<div class="container-fluid"  >
    <div class="row top-buffer" >
        <div class="col-md-7" id="card-header" >
            <div class="col-md-5">
                <div class="card-title" >
                    <h3><?php echo "Title: {$blog_title}" ?></h3>
                    <h5 ><?php echo $formatted_datetime ?></h5>
                    <br>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="btn-toolbar" role="toolbar" aria-label="blog_options">
                <div class="btn-group-vertical" aria-label="blog_options">
                    <button onclick="" class="btn btn-primary "><span class="glyphicon glyphicon-pencil"></span>
                    </button>
                    <button onclick="" class="btn btn-danger "><span class="glyphicon glyphicon-trash"></span>
                    </button>
                    <button onclick="" class="btn btn-default "><span class="glyphicon glyphicon-cog"></span></button>
                </div>
            </div>


        </div>


    </div>
    <br>
    <div class="row top-buffer">
        <div class="col-md-7" id="card-content">
            <br>
            <div class="card-content">
                <p><?php echo $blog_content ?></p>
            </div>
        </div>




    </div>

</div>


<div class="container-fluid" id="comment-section">
    <div class="row top-buffer">
        <div class="col-md-3">
            <h2>Comments</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-7" id="comment">

        </div>
    </div>
</div>

<br>



<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <form action="blog.php" method="post">
            <h4>Comment</h4><textarea style="width:95%;"  name="blog_comment" placeholder="Write your comment here..."></textarea><br />
            <button type="submit" class="btn btn-primary">Add a comment</button>
                <?php
                if (isset($_POST["blog_comment"])){
                    $comment_content = $_POST["blog_comment"];
                    validate_insert_comment_input($current_blogID,$userid,$comment_content);
                }
                ?>
            </form>
        </div>
    </div>
</div>


    <br/><br/>



<?php echo message() ?>


<a href="logout.php">Logout</a>

<!--end of body-->
<?php include("../includes/footer.php"); ?>



