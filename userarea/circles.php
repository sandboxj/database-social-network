<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require("../server/functions_circle.php");?>
<?php $page_title="Circles"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<?php
$userid = $_SESSION['UserID'];

if (isset($_POST['create-circle'])){
    $circle_title = $_POST['circle_title'];
    $circle_photoID = 1;

    //circle photoID was left to 1 for now
    $title_empty = validate_circle_name($userid, $circle_title);

    if($title_empty == false) {
        $title_exists = check_circle_title($userid, $circle_title);

        if($title_exists == true ){
            //already exists
            echo "<script>alert('You already have a circle with this name, please change the name')</script>";
        }else{
            //method automatically adds admin as a circle member
            insert_new_circle($userid, $circle_title, $circle_photoID);
            //redirect_to("circles.php");
        }



    }else{
        echo "<script>alert('Circle name cannot be empty')</script>";
    }


}
?>

<section class="jumbotron circle-jumbotron">
    <div class="container">
        <div class="row text-center">
            <h1 style="color: dodgerblue;">Your Circles</h1>
        </div>

    </div>
</section>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-record"></span> Create a circle</button>
        </div>
    </div>
</div>


<br>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create a new circle:</h4>
            </div>
            <div class="modal-body">

                <div class="row content">

                    <div class="col-md-3">
                        <img class="img-responsive img-circle" src="img/1.jpg" />
                        <!--            HAVE THE FUNCTION FROM THE UPLOAD PHOTOS-->
                    </div>
                    <div class="col-md-3">
                        <input type="submit" value="Upload Photo" class="btn btn-default"/>
                    </div>
                    <div class="col-md-4 pull-right">
                        <form action="circles.php" method="post">
                            <label for="circle_name">Circle Name:</label>
                            <input type="text" value="" name="circle_title" placeholder="Circle Name" required>
                            <br>
                            <br>
                            <input type="submit" name="create-circle" value="Create Circle" class="btn btn-primary"/>
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

<br>
		<!--Insert code here-->
<div class="container">
    <div class="row ">

        
    <?php $circle_results = find_user_circles($userid);

        while($circles = mysqli_fetch_assoc($circle_results)){

            $circleID = $circles['CircleID'];

            //name to display
            $circle_name = $circles['CircleTitle'];
            $circle_photoID = $circles['CirclePhotoID'];

            $member_count = count_circle_members($circleID);


        ?>

            <a href="circle.php?circleID=<?php echo $circleID;?>&count=<?php echo $member_count?>">
   <div class="col-md-6 polaroid individual-circle">

        <div class="col-md-3 col-centered">
            <img class="img-responsive img-circle" src="img/1.jpg" />
        </div>
        <div class="col-md-8 col-centered" >
            <h4><?php echo $circle_name ?></h4>
            <hr>
            <h5>Circle members: <?php echo $member_count ?> </h5>
        </div>

</div>
            </a>

      <?php  } //closing the while loop

      mysqli_free_result($circle_results);?>
        </div>
    </div>
</div>

<br>
<br>


<!-- Trigger the modal with a button -->



		<hr />


<?php include("../includes/footer.php"); ?>
