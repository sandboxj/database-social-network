<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require("../server/functions_circle.php");?>
<?php require("../server/user_functions.php");?>
<?php require("../server/functions_friends.php");?>
<?php require("../server/functions_messages.php");?>
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

            <div class="col-md-3">
                <div class="container" style="border-style: solid;">

                </div>

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


<div class="container">

    <div class="row">
        <div class="col-md-5">


        <h2>Circle Members:</h2>
        <?php
while($circle_members=mysqli_fetch_assoc($circle_members_results)){
    $member_userID = $circle_members['MemberUserID'];


    $full_name_array = find_full_name($member_userID);

    $first_name = $full_name_array['FirstName'];
    $last_name = $full_name_array['LastName'];



?>


<div class="row">
        <div class="col-md-4">
<!--            COULD include the profile picture here-->
            <ul class="list-group">
                <li class="list-group-item" role="presentation" class="member-name"><a href="user_profile.php?id=<?php echo $member_userID ?>"><?php echo "{$first_name} {$last_name}";?></a></li>
            </ul>

        </div>

</div>

<?php } ?>
        </div>
        <div class="col-md-7">

        <h2>Circle Messages</h2>
            <?php
            $circle_messages_results_db = check_circle_messages($circleID);
            while($circle_messages = mysqli_fetch_assoc($circle_messages_results_db)){
                $message_title = $circle_messages['Title'];
                $message_content = $circle_messages['Content'];
                $message_senderID = $circle_messages['SenderUserID'];
                $message_sender_full_name = find_full_name($message_senderID);
                $message_sender_first_name = $message_sender_full_name['FirstName'];
                $message_sender_last_name = $message_sender_full_name['LastName'];

                $date_format = strtotime($circle_messages['TimeSent']);
                $date_final = date("D, jS F Y, H:i", $date_format);

            ?>

        <div class="row">
            <div class="col-md-7">
                <p><? echo "{$message_sender_first_name} {$message_sender_last_name} said:" ?></p>
                <h3>Title: <?php echo $message_title; ?></h3>

                <p><?php echo $message_content; ?></p>
                <p>Date: <?php echo $date_final; ?></p>
            </div>
        </div>
        <?php } //closing while loop?>
        </div>

        </div>
    </div>


<!-- Trigger the modal with a button -->
<?php if($viewer_userID == $circle_adminID){?>
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add a Friend</button>
<?php } ?>
<br>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Choose a friend to add to the circle:</h4>
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
    $is_in_circle_check = is_in_specific_circle($friendID, $circleID);

   
    
//PUT THE ID IN THE VALUE AND THE NAME

//do not display the user if it is already in the circle.
    if(!$is_in_circle_check){
        echo $friend_output;
       
    }
         
         } ?>
        </ul>

        <button type="submit" class="btn btn-primary" name="invitation" >Add Friends</button>

        </form>
      </div>
      <!--<div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div>

  </div>
</div>

<?php mysqli_free_result($friend_results_db); ?>

<!--end of body-->
<?php include("../includes/footer.php"); ?>
