<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require("../server/circle_functions.php");?>
<?php require("../server/user_functions.php");?>
<?php require("../server/functions_friends.php");?>
<?php $page_title="Circles"?>
<?php confirm_logged_in(); ?>

<?php

$viewer_userID = $_SESSION['UserID'];

if(isset($_POST['delete_circle'])){
    $circleID = $_GET['circleID'];

    delete_circle($circleID);
    redirect_to("../userarea/circles.php");
}

if(isset($_POST['leave_circle'])){
    $circleID = $_GET['circleID'];

    leave_circle($circleID, $viewer_userID);
    redirect_to("../userarea/circles.php");
}


if(isset($_POST['invitation'])){
   
    $circleID = $_GET['circleID'];
    $invited_friendsID = $_POST['invited_friends'];

    echo $circleID;
    foreach($invited_friendsID as $invited_friendID){
        
        add_circle_member($circleID, $invited_friendID);
    }



}
?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<?php


$circleID = $_GET['circleID'];
$circle_member_count = $_GET['count'];


$circle_members_results = find_circle_members($circleID);


//packaged previous queries into a single one
$circle_details =  find_circle_details($circleID);
$circle_photoID = $circle_details['circle_photo'];
$circle_title = $circle_details['circle_title'];
$circle_adminID = $circle_details['circle_admin'];








?>



<div class="jumbotron">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
            <div class="circle-profile">

                <img class="img-responsive img-circle" src="img/1.jpg"  />             

            </div>
            </div>
            <div class="col-md-4">
                <h2><?php echo $circle_title?></h2>
                <h4>Number of members: <?php echo $circle_member_count; ?> </h4>
                </div>


            <div class="col-md-2 pull-right">
                 <form action="circle.php?circleID=<?php echo $circleID; ?>" method="post">

                 
                <?php if($viewer_userID == $circle_adminID){?>
                <button type="submit" onclick="return confirm('Are you sure you want to delete this circle?')" name="delete_circle" class="btn btn-danger "><span class="glyphicon glyphicon-trash"></span>
                </button>
                <?php } else {?>
                <button type="submit" onclick="return confirm('Are you sure you leave this circle?')" name="leave_circle" class="btn btn-primary btn-block">Leave Circle</button>
                <?php } //closing else statement; the button only appears to non-admin members?>
            </form>
            </div>

            
        </div>
    </div>
</div>


<div class="container circle-members">

    <div class="row">

        <h1 id="circle-members-title">Circle Members:</h1>
        <?php
while($circle_members=mysqli_fetch_assoc($circle_members_results)){
    $member_userID = $circle_members['MemberUserID'];
    

    $full_name_array = find_full_name($member_userID);

    $first_name = $full_name_array['FirstName'];
    $last_name = $full_name_array['LastName'];



?>



        <div class="col-md-7">
<!--            COULD include the profile picture here-->
            <ul>
                <li class="member-name"><a href="user_profile.php?id=<?php echo $member_userID ?>"><?php echo "{$first_name} {$last_name}";?></a></li>
            </ul>

        </div>

<?php } ?>

        <br>
        <br>
        <br>
        <br>

    </div>


<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Invite a Friend</button>
<br>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Choose a friend to invite to the circle:</h4>
      </div>
      <div class="modal-body">
        <p>Select friends from the list below:</p>

        <form action="circle.php?circleID=<?php echo $circleID?>&count=<?php echo $circle_member_count; ?>" method="post">
        <ul class="list-group">

<?php $friend_results_db = find_accepted($viewer_userID);
while ($friend_results  = mysqli_fetch_assoc($friend_results_db)){
    $friendID = $friend_results['UserID'];
    $friend_first_name = $friend_results['FirstName'];
    $friend_last_name  = $friend_results['LastName'];
 
    
    $friend_output = "<li class='list-group-item'><input type='checkbox' name='invited_friends[]' value='{$friendID}'/> {$friend_first_name} {$friend_last_name}</li>";
    $check = is_in_specific_circle($friendID, $circleID);

   
    
//PUT THE ID IN THE VALUE AND THE NAME

//do not display the user if it is already in the circle.
    if(!$check){
        echo $friend_output;
       
    }
         
         } ?>
        </ul>

        <button type="submit" class="btn btn-primary" name="invitation" >Invite Friends</button>

        </form>
      </div>
      <!--<div class="modal-footer">
        
  
  
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div>

  </div>
</div>

<?php mysqli_free_result($friend_results_db); ?>
<a href="logout.php">Logout</a>
<!--end of body-->
<?php include("../includes/footer.php"); ?>
