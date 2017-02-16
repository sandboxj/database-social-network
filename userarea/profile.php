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
              <link rel="stylesheet" href="../styles/ProfileThumbnail.css">
              <div class="portrait">
                <img src= <?php $result = find_profile_pic($_SESSION["UserID"]);
                while($pic = mysqli_fetch_assoc($result)) {
                $output = $pic["FileSource"];
                echo $output;
                }?>>
                </div>
            <?php echo message();?>
            <form action="profile.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">
            </form>       
            </div>
            <div class="col-sm-8">
              First name: <?php echo $_SESSION["FirstName"]?> <br /><br />
              Last name: <?php echo $_SESSION["LastName"]?> <br /><br />
              Date of Birth: <?php echo $_SESSION["DateOfBirth"]?> <br /> <br />
            </div>
        </div>
        <hr />
        <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
