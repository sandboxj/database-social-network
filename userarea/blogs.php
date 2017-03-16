<?php require("../server/functions_blog.php");?>
<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php $page_title="{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Blogs"?>
<?php confirm_logged_in(); ?>


<?php
//currently connected user
$userid = $_SESSION['UserID'];


/*
 * After pressing the post button. if statement handles validation
 * and insertion into the database.
 */
if(isset($_POST["blog_post"])){

    $blog_title = $_POST['blog_title'];
    $blog_content = $_POST['blog_content'];
    $access_rights=$_POST['access'];

    $title_empty = validate_blog_title($userid, $blog_title);
    $content_empty = validate_blog_content($userid, $blog_title);

    if($title_empty == false and $content_empty == false){
        //input is valid, check the title
        $title_exists = check_blog_title($userid, $blog_title);

        if($title_exists == true){
            echo "<script>alert('Blog title already exists')</script>";
        }else{
            //post was successful
            insert_blog_post($userid, $blog_title, $blog_content, $access_rights);
            //redirect_to("blogs.php");
        }


    }elseif ($title_empty == true){
        echo "<script>alert('Blog title cannot be empty')</script>";

    }elseif ($content_empty == true){
        echo "<script>alert('Blog content cannot be empty')</script>";

    }





}

?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<section class="jumbotron jumbotron-blog">
<div class="container">
    <div class="row text-center">


        <h1 style="padding-top: 60px;">Your Blogs</h1>

    </div>

</div>
</section>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-pencil"></span> New blog post</button>
        </div>
    </div>
</div>

<!-- Trigger the modal with a button -->

<br>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Make a new blog post:</h4>
            </div>
            <div class="modal-body">

                <div class="row ">
                    <div class="col-md-12 blog-post-area" >


                        <form action="blogs.php" method="post">
                            <h4>Title</h4><textarea rows="1" style="width: 80%" name="blog_title" required></textarea><br />
                            <h4>Content</h4><textarea class="col-md-12" rows="20" name="blog_content" contenteditable="true" required></textarea><br />

                            <br>
                            <br>

                            <select  name ="access">
                                <option value="0">Only me</option>
                                <option value="1" selected="1">Friends</option>
                                <option value="2">Everybody</option>
                                <option value="3">Circles</option>
                                <option value="4">Friends of friends</option>

                            </select>

                            <button type="submit" class="btn btn-primary pull-right" name="blog_post" >Post</button>

                            <!--                                <input type="submit" name="blog_post" value="Post" />-->

                        </form>
                    </div>

                </div>



            </div>

            <!--<div class="modal-footer">




              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>-->

        </div>

    </div>
</div>


<?php echo message()?>






<!--Blogs below-->
<div class="container-fluid"  >





<?php
global $conn;

    $blog_results = find_blogs($_SESSION["UserID"]);

    if(mysqli_num_rows($blog_results)>0) {




        while ($blog_posts = mysqli_fetch_assoc($blog_results)) {

            //blog_title variable used below; using $title here for the output
            $title = $blog_posts["Title"];


            //escaping the title to put in db
            $escaped_title = mysqli_real_escape_string($conn, $blog_posts["Title"]);

            $date_posted = $blog_posts["DatePosted"];
            $formatted_date = display_formatted_date($date_posted);

            $title_output = "Title: <td> {$title} </td><br />";


            ?>
            <a href='blog.php?title=<?php echo $escaped_title;?>'>
        <div class="polaroid col-md-4 individual-blog">

            <!--Inside WHILE, display every individual blog-->


                <?php echo $title_output; ?>
                <h6><?php echo $formatted_date ?></h6>



        </div>
            </a>
            <?php

        }//closing while

    }//closing if

    else{
        echo "<br> <h1>You currently have no blog posts. </h1> <br> <h3>Try creating a new one</h3>
       <br><h3>Share your stories</h3>";
    }
    mysqli_free_result($blog_results);
?>



</div>


<?php include("../includes/footer.php"); ?>
