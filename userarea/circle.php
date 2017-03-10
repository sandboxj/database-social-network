<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require("../server/circle_functions.php");?>
<?php require("../server/user_functions.php");?>
<?php $page_title="Circles"?>
<?php confirm_logged_in(); ?>

<?php
if(isset($_POST['delete_circle'])){
    $circleID = $_GET['circleID'];

    delete_circle($circleID);
    redirect_to("../userarea/circles.php");
}

if(isset($_POST['invited_friends[]'])){
    $circleID = $_GET['circleID'];

 
 var_dump($_POST['invited_friends[]']);

}
?>

<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php


$viewer_userID = $_SESSION['UserID'];
$circleID = $_GET['circleID'];

$circle_title = find_circle_title($circleID);

$circle_photoID = find_circle_photoID($circleID);

$circle_members_results = find_circle_members($circleID);

$circle_member_count = count_circle_members($circleID);

$circle_adminID = find_circle_admin($circleID);

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

<?php if($viewer_userID == $circle_adminID){?>
            <div class="col-md-2 pull-right">
                 <form action="circle.php?circleID=<?php echo $circleID; ?>" method="post">


                <button type="submit" onclick="return confirm('Are you sure you want to delete this circle?')" name="delete_circle" class="btn btn-danger "><span class="glyphicon glyphicon-trash"></span>
                </button>

            </form>
            </div>

            <?php } ?>
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

        <form action="circle.php?circleID=<?php echo $circleID?>" method="post">
        <ul class="list-group">
        <li class="list-group-item"><input type="checkbox" name="invited_friends[]" value="Name 1"/> Name Here</li>
        <li class="list-group-item"><input type="checkbox" name="invited_friends[]" value="Name 2"/> Name Here</li>
        <li class="list-group-item"><input type="checkbox" name="invited_friends[]" value="Name 3"/> Name Here</li>
        </ul>
        <button type="submit" class="btn btn-primary" >Invite Friends</button>
        </form>
      </div>
      <!--<div class="modal-footer">
        
  
  
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div>

  </div>
</div>


<a href="logout.php">Logout</a>
<!--end of body-->
<?php include("../includes/footer.php"); ?>
