<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require("../server/circle_functions.php");?>
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
    //method automatically adds admin as a circle member
    insert_new_circle($userid, $circle_title, $circle_photoID);
}
?>

		<h2>Your Circles</h2>
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
    
   <div class="col-md-6 ">
        <div class="col-md-3 col-centered">
            <img class="img-responsive img-circle" src="img/1.jpg" />
        </div>
        <div class="col-md-8 col-centered" >
            <h4><?php echo $circle_name ?></h4>
            <hr>
            <h5>Circle members: <?php echo $member_count ?> </h5>
            <br>
            <a href="circle.php?circleID=<?php echo $circleID;?>&count=<?php echo $member_count?>" class="btn btn-primary btn-block" type="button">See More</a>
        </div>

</div>

      <?php  } //closing the while loop

      mysqli_free_result($circle_results);?>
        </div>
    </div>
</div>

<br>
<br>


<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Create a circle</button>
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


		<hr />
		<a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
