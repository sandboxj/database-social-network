<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require("../server/functions_circle.php");?>
<?php require("../server/functions_user.php");?>
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

        insert_new_circle_member($circleID, $invited_friendID);
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

$circle_title = $circle_details['circle_title'];
$circle_adminID = $circle_details['circle_admin'];


?>



<div class="jumbotron jumbotron-circle">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
           
            </div>
            <div class="col-md-4">
                <h1><?php echo $circle_title?></h1>
                <h4 style="color:white;">Number of members: <?php echo $circle_member_count; ?> </h4>
            </div>

            <div class="col-md-3">


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
                <br>
                <br>
                <!-- Trigger the modal with a button -->
                <?php if($viewer_userID == $circle_adminID){?>
                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Add a Friend</button>
                <?php } ?>
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


                <div class="row" >


                    <div class="col-md-12">
                        <!--            COULD include the profile picture here-->
                        <ul class="nav nav-pills nav-stacked" style="background-color: white;">
                            <?php if($viewer_userID == $member_userID){
                                ?>
                                <li role="presentation"><a href="profile.php?id=<?php echo $member_userID ?>"><i class="glyphicon glyphicon-user"></i> <?php echo "{$first_name} {$last_name}";?></a></li>
                            <?php }else{
                                ?>
                                <li role="presentation"><a href="user_profile.php?id=<?php echo $member_userID ?>"><i class="glyphicon glyphicon-user"></i> <?php echo "{$first_name} {$last_name}";?></a></li>
                            <?php } ?>

                        </ul>

                    </div>

                </div>

            <?php } ?>

            <br>
            <br>
            <br>

            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2>Send a message to this circle:</h2>
                        </div>
                        <div class="panel-body">
                            <form name="form_message" method="post" action="circle.php?circleID=<?php echo $circleID ?>&count=<?php echo $circle_member_count?>">
                                <table class="col-md-10">
                                     <tr>
                                        <td height="10"></td>
                                         <input type="hidden" value="C<?php echo $circleID; ?>" name="to_user">
                                    </tr>
                                    <tr>
                                        <td><label class="message_label" for="title">Title:</label></td>
                                        <td><input type="text" class="form-control" id="title" name="title" value=""
                                                   required placeholder="Subject">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="10"></td>
                                    </tr>
                                    <tr>
                                        <td align="top"><label class="message_label" for="message_content_field">Message:</label></td>
                                        <td><textarea class="form-control" contenteditable="true" id="message_content_field"
                                                      rows="5" style="width: 100%" aria-describedby="message_helper"
                                                      name="message_content"
                                                      required></textarea>
                                            <small id="message_helper" class="form-text text-muted">Max. 2500 Characters</small></td>
                                    </tr>
                                    <tr>
                                        <td height="10"></td>
                                        <td>
                                            <input type="submit" name="send" value="Send Message" class="btn btn-primary"/><br>
                                            <?php
                                            require_once("../server/validation_message.php");
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </form>

                        </div>
                        <?php
                        if ($check == false) { ?>
                            <div class="alert alert-danger"><?php echo "$message" ?></div>
                        <?php };
                        if ($check2 == true) { ?>
                            <div class="alert alert-success"><?php echo "$message" ?></div>
                        <?php }; ?>
                    </div>

                </div>

            </div>
        </div>
        <div class="col-md-6 col-md-offset-1 " >

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

        <div class="row polaroid-circle-messages">
            <div class="col-md-7">
                <p><?php echo $date_final; ?></p>
                <p><? echo "{$message_sender_first_name} {$message_sender_last_name} said:" ?></p>
                <h3>Title: <?php echo $message_title; ?></h3>

                <p><?php echo $message_content; ?></p>

            </div>
        </div>
                <br>
        <?php } //closing while loop?>
        </div>

        </div>
    </div>





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
