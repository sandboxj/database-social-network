<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_user.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/validation_upload.php");?>
<?php require_once("../server/validation_profile.php");?>
<?php $page_title= "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Profile"?>
<?php confirm_logged_in(); ?>

<?php
$userid = $_SESSION['UserID'];
$privacy_setting = find_user_privacy_setting($userid);

if(isset($_GET['updated_privacy'])){
    $updated_privacy = $_GET['privacy_setting'];
    echo $updated_privacy;

    $result = update_privacy_setting($userid, $updated_privacy);
    if($result){

    }
    redirect_to("profile.php");

}
?>



<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<?php
$collection_id = "Profilepictures" . $_SESSION['UserID'];
$pic_result = find_profile_pic($_SESSION["UserID"]);
$profile_picture = mysqli_fetch_assoc($pic_result);
$profile_picture_src = file_exists("img/" . $collection_id . "/" . $profile_picture["FileSource"]) ? "img/" . $collection_id . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
$uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
mysqli_free_result($pic_result);
?>

<?php
if ($message != "") {
        if($check) {
    ?>
    <div class="alert alert-danger">
      <?php echo $message; ?>
    </div>
    <?php } else { ?>
    <div class="alert alert-success">
      <?php echo $message; ?>
    </div>
    <?php
    }
}
?>
<section class="jumbotron jumbotron-profile">
    <div class="container">
        <div class="row">
            <div class="col-md-2">

            </div>
               <div class="col-md-4 text-center">
                   <div class="container">
                   <img src="<?php echo $uncached_src ?>" class="img-responsive img-circle" alt="Profile picture">
                   </div>
                <h2><?php echo $_SESSION["FirstName"] . " " . $_SESSION["LastName"]?></h2>
                   <br><br>

                   <form class="hidden edit_profile" action="profile.php" method="post" enctype="multipart/form-data">

                       <input type="file"  class="btn btn-default btn-block" name="fileToUpload" id="fileToUpload">
                       <input type="text" name="collectionid" value="<?php echo $collection_id?>" class="hidden" readonly>
                       <input type="submit" class="btn btn-primary pull-left btn-block" value="Upload Image" name="submit">
                   </form>

               </div>
            <div class="col-md-2">


            </div>
            <div class="col-md-4">
                <?php $found_user = find_user_by_email($_SESSION["Email"]); ?>
                <!--Edits dont do anything yet-->
                <h2>Profile Details:</h2>
                <form method="post">
                    <label class="hidden edit_profile" for="first_last_name">Name:</label>
                    <input type="text" id="first_last_name" name="first_name" value="<?php echo $found_user["FirstName"]?>" class="hidden edit_profile form-control" style="width: 100%; display: inline" required>
                    <br><br>
                        <input type="text" name="last_name" id="last_name" value="<?php echo $found_user["LastName"]?>" class="hidden edit_profile form-control" style="width: 100%; display: inline"><br/><br>
                        <label for="dob">Date of Birth: <span class="edit_profile"><?php echo $found_user["DateOfBirth"]?></span></label>
                        <input type="date" id="dob" name="date_of_birth" value="<?php echo $found_user["DateOfBirth"]?>" class="hidden edit_profile form-control" style="width: 100%; display: inline"><br /> <br />
                        <label for="location" >Location: <span class="edit_profile"><?php echo $found_user["CurrentLocation"]?></span></label>
                        <input type="text" id="location" name="location" value="<?php echo $found_user["CurrentLocation"]?>" class="hidden edit_profile form-control" style="width: 100%; display: inline"><br/><br/>
                        <label for="email" >Email: <span class="edit_profile"><?php echo $found_user["Email"]?></span></label>
                        <input type="text" id="email" name="email" value="<?php echo $found_user["Email"]?>" class="hidden edit_profile form-control" style="width: 100%; display: inline"><br/><br/>
                        <label for="phone" >Phone Number: <span class="edit_profile"><?php echo $found_user["PhoneNumber"]?></span></label>
                        <input type="text" id="phone" name="phone_number" value="<?php echo $found_user["PhoneNumber"]?>" class="hidden edit_profile form-control" style="width: 100%; display: inline"><br/><br/>
                        <label for="interest" >Interest: <span class="edit_profile"><?php echo $interest = $found_user["Interest"]?></span></label>
                        <select name="interests" id="interest" class="hidden edit_profile form-control">
                        <option value="None" <?php echo ($interest=='None') ? "selected" : "" ?>>None</option>
                        <option value="Politics" <?php echo ($interest=='Politics') ? "selected" : "" ?>>Politics</option>
                        <option value="Music" <?php echo ($interest=='Music') ? "selected" : "" ?>>Music</option>
                        <option value="Database Systems" <?php echo ($interest=='Database Systems') ? "selected" : "" ?>>Database Systems</option>
                        <option value="Food" <?php echo ($interest=='Food') ? "selected" : "" ?>>Food</option>
                        <option value="Philosophy" <?php echo ($interest=='Philosophy') ? "selected" : "" ?>>Philosophy</option>
                        <option value="Movies" <?php echo ($interest=='Movies') ? "selected" : "" ?>>Movies</option>
                        <option value="Sports" <?php echo ($interest=='Sports') ? "selected" : "" ?>>Sports</option>
                        <option value="Travelling" <?php echo ($interest=='Travelling') ? "selected" : "" ?>>Travelling</option>
                        <option value="Gaming" <?php echo ($interest=='Gaming') ? "selected" : "" ?>>Gaming</option>
                        <option value="Reading" <?php echo ($interest=='Reading') ? "selected" : "" ?>>Reading</option>
                    </select>
                    <button class="btn btn-primary edit_profile hidden" type="submit" name="edit_profile" value="submit">Save changes</button>
                </form>
                <br>
                <div class="btn-toolbar" role="toolbar" aria-label="blog_options">
                    <div class="btn-group-horizontal" aria-label="blog_options">
                        <button class="btn btn-primary edit_profile" onclick="$('.edit_profile').toggleClass('hidden');">Edit profile</button>
                        <button class="btn btn-default edit_profile hidden" onclick="$('.edit_profile').toggleClass('hidden');">Cancel</button>
                        <button class="btn btn-primary edit_profile" data-toggle="modal" data-target="#myModal">Change Password</button>
                        <div id="myModal" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="title">Change password</h4>
                              </div>
                              <div class="modal-body">
                                <div class="row ">
                                  <div class="col-md-12 blog-post-area" >
                                    <form action="profile.php" method="post">
                                      <div class="form-group required">
                                        <input type="password" class="form-control" id="new_password" name="new_password" value="" placeholder="New password" aria-describedby="password_helper" required="">
                                      </div>
                                      <div class="form-group required">
                                        <input type="password" class="form-control" id="password-confirm" name="password_confirm" value="" placeholder="Confirm Password" aria-describedby="password_confirm_helper" required="">
                                      </div>
                                      <button type="submit" class="btn btn-primary pull-right" name="change_password" >Change password</button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="dropdown">
                            <button  type ="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                 <span class="glyphicon glyphicon-cog"></span>
                                 <?php echo $privacy_setting?></button>
                             <ul class="dropdown-menu dropdown-menu-right" id="privacy">
                                 <li class="dropdown-header privacy-menu" style="font-weight: bold; font-size: 13px; margin-top: 3px;">Update Privacy Settings:</li>
                                 <li class="privacy-menu" aria-disabled="true" style="font-size: 6px;">-----------------------------------------------------------------------------------------</li>
                                 <li class="privacy-menu"><a href="profile.php?updated_privacy=<?php echo true?>&privacy_setting=<?php echo "0" ?>">Friend</a></li>
                                 <li class="privacy-menu"><a href="profile.php?updated_privacy=<?php echo true?>&privacy_setting=<?php echo "1" ?>">Friends of friends</a></li>
                                 <li class="privacy-menu"><a href="profile.php?updated_privacy=<?php echo true?>&privacy_setting=<?php echo "2" ?>">Everyone</a></li>
                                 <li class="privacy-menu"><a href="profile.php?updated_privacy=<?php echo true?>&privacy_setting=<?php echo "3" ?>">Unsearchable</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8" style="text-align: center">
                    <form method="post">
                    <button type="submit" class="btn btn-danger edit_profile hidden" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? Deletion is permanent.')">Delete account</button>
                    </form>
                </div>
            </div>

            </div>

        </div>
</section>
<?php include("../includes/footer.php"); ?>
