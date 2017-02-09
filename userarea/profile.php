<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/validation_upload.php");?>
<?php $page_title="Profile"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

        <h2>Your Profile</h2>
        <div class="row">
            <div class="col-sm-4">
            <?php
                
                $pic_result = find_profile_pic($_SESSION["UserID"]);
                $profile_picture = mysqli_fetch_assoc($pic_result);
                $profile_picture_src = "img/" . $profile_picture["PhotoID"];
                
            ?>
            <img src="<?php echo htmlentities($profile_picture_src); ?>" alt="Profile picture">
            <?php echo message();?>
            <form action="profile.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">
            </form>       
            </div>
            <div class="col-sm-8">
                Last name: <br /><br />
                First name: <?php echo $_SESSION["FirstName"]?> <br /><br />
                Date of Birth: <br /> <br />
            </div>
        </div>
        <hr />
        <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
