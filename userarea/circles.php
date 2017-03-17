<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions_user.php");?>
<?php require("../server/functions_circle.php");?>
<?php $page_title="Circles"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<?php
$userid = $_SESSION['UserID'];

if (isset($_POST['create-circle'])){

        $circle_title = $_POST['circle_title'];


        //circle photoID was left to 1 for now
        $title_empty = validate_circle_name($userid, $circle_title);

        if ($title_empty == false) {
            $title_exists = check_circle_title($userid, $circle_title);

            if ($title_exists == true) {
                //already exists
                echo "<script>alert('You already have a circle with this name, please change the name')</script>";
            } else {
                //method automatically adds admin as a circle member
                insert_new_circle($userid, $circle_title);
                //redirect_to("circles.php");
            }


        } else {
            echo "<script>alert('Circle name cannot be empty')</script>";
        }


}
?>

<section class="jumbotron jumbotron-circle">
    <div class="container">
        <div class="row text-center">
            <h1>Your Circles</h1>
        </div>

    </div>
</section>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-record"></span> Create a circle</button>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create a new circle:</h4>
            </div>
            <div class="modal-body" id="circle-modal">

                <div class="row content">

                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4 ">
                        <form action="circles.php" method="post">
                            <div class="form-group">
                            <label for="circle_name" class="control-label">Circle Name:</label>
                            <input class="" class="form-control" type="text" value="" name="circle_title" placeholder="Circle Name" required>
                            <br>
                            <br>
                            <!--                            <input type="hidden" name="circleID" value="--><?php //echo $circleID; ?><!--">-->
                                <input type="submit" name="create-circle" value="Create Circle" class="btn btn-primary btn-block"/>
                            </div>
                        </form>

                    </div>
                    <div class="col-md-4">
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



<br>
		<!--Insert code here-->
<div class="container">
    <div class="row ">

        
    <?php $circle_results = find_user_circles($userid);

        while($circles = mysqli_fetch_assoc($circle_results)){
            $circle_admin = $circles['CircleAdminUserID'];
            $circleID = $circles['CircleID'];

            $circle_admin_full_name=find_full_name($circle_admin);
            $circle_admin_firstname =$circle_admin_full_name['FirstName'];
            $circle_admin_lastname =$circle_admin_full_name['LastName'];

            //name to display
            $circle_name = $circles['CircleTitle'];


            $member_count = count_circle_members($circleID);


        ?>

            <a href="circle.php?circleID=<?php echo $circleID;?>">
   <div class="col-md-6 polaroid-circle">


        <div class="col-md-3 content-center text-center">
            <br><br>
            <i class="glyphicon glyphicon-record" style="font-size: 60px;"></i>
        </div>
        <div class="col-md-9 col-centered" >
            <h4>Name: <?php echo $circle_name ?></h4>
            <hr>
            <h5>Circle members: <?php echo $member_count ?> </h5>
            <?php if($circle_admin == $userid){
                echo "<br><h5>You are the admin of this circle</h5>";
            }else{
                echo "<br><h5>Circle Admin: {$circle_admin_firstname} {$circle_admin_lastname}</h5>";
            }
            ?>


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



<?php include("../includes/footer.php"); ?>
