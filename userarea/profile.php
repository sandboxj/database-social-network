<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/validation_upload.php");?>
<?php require_once("../server/validation_profile.php");?>
<?php $page_title= "{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Profile"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <h2><?php echo $_SESSION["FirstName"] . " " . $_SESSION["LastName"]?></h2>
        </div>
        <div class="col-md-4" pull-right>
            <br>
            <div class="btn-toolbar" role="toolbar" aria-label="blog_options">
                <div class="btn-group-horizontal" aria-label="blog_options">
            <button class="btn btn-primary" onclick="$('.edit_profile').toggleClass('hidden');">Edit profile</button>
                    <button class="btn"> <span class="glyphicon glyphicon-cog"></span> Privacy Settings</button>
                </div>
            </div>
        </div>
    </div>
</div>

<br>
<br>

<div class="container-fluid">
<div class="row">
            <div class="col-sm-4">

                <?php
                    $collection_id = "Profilepictures" . $_SESSION['UserID'];
                    $pic_result = find_profile_pic($_SESSION["UserID"]);
                    $profile_picture = mysqli_fetch_assoc($pic_result);
                    $profile_picture_src = file_exists("img/" . $collection_id . "/" . $profile_picture["FileSource"]) ? "img/" . $collection_id . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
                    $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
                    mysqli_free_result($pic_result);
                ?>

                <img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Profile picture">
                <?php echo message();?>

            <form class="hidden edit_profile" action="profile.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="text" name="collectionid" value="<?php echo $collection_id?>" class="hidden" readonly>
                <input type="submit" value="Upload Image" name="submit">
            </form>       
            </div>
            <div class="col-sm-8">

              <?php $found_user = find_user_by_email($_SESSION["Email"]); ?>
              <!--Edits dont do anything yet-->
              <form method="post">
              Date of Birth: <span class="edit_profile"><?php echo $found_user["DateOfBirth"]?></span>
              <input type="date" name="date_of_birth" value="<?php echo $found_user["DateOfBirth"]?>" class="hidden edit_profile form-control" style="width: 20%; display: inline"><br /> <br />
              Location: <span class="edit_profile"><?php echo $found_user["CurrentLocation"]?></span>
              <input type="text" name="location" value="<?php echo $found_user["CurrentLocation"]?>" class="hidden edit_profile form-control" style="width: 20%; display: inline"><br/><br/>
              Email: <span class="edit_profile"><?php echo $found_user["Email"]?></span>
              <input type="text" name="email" value="<?php echo $found_user["Email"]?>" class="hidden edit_profile form-control" style="width: 20%; display: inline"><br/><br/>
              Phone Number: <span class="edit_profile"><?php echo $found_user["PhoneNumber"]?></span>
              <input type="text" name="phone_number" value="<?php echo $found_user["PhoneNumber"]?>" class="hidden edit_profile form-control" style="width: 20%; display: inline"><br/><br/>
              <button class="btn btn-primary edit_profile hidden" type="submit" name="edit_profile" value="submit">Save changes</button>
              <button class="btn edit_profile hidden" onclick="$('.edit_profile').toggleClass('hidden');">Cancel</button>
              </form>
            </div>
        </div>
</div>
        <hr />
        <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
