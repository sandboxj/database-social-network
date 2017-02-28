<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/validation_friending.php");?>
<?php $query = "SELECT * FROM user u
                WHERE u.UserID like '{$_GET['id']}'";
                $displayed_user = mysqli_query($conn, $query);
                confirm_query($displayed_user);
                $user = mysqli_fetch_assoc($displayed_user) ?>
<?php $page_title= "{$user["FirstName"]} {$user["LastName"]}'s Profile"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

        <h2><?php $name = "{$user["FirstName"]} {$user["LastName"]}";
		          echo $name ?></h2><br/>
        <div class="row">
            <div class="col-sm-4">
              <div class="portrait">
                <img src= <?php $result = find_profile_pic($user["UserID"]);
                while($pic = mysqli_fetch_assoc($result)) {
                $output = "../userarea/img/" . $pic["FileSource"];
                echo $output;
                }?> class="img-rounded" alt="Cinque Terre" width="236" height="304"><br/><br/>
                </div>
				<form action="user_blogs.php" method="post">
          <input type ="hidden" name ="user" value ="<?php echo $user["UserID"]?>">
						<input type="submit" name="blogs" value="Blogs">
				</form>
				<form action="user_collections.php" method="post">
          <input type ="hidden" name ="user" value ="<?php echo $user["UserID"]?>">
						<input type="submit" name="photos" value="Photos">
				</form>
				<form action="user_friends.php" method="post">
            <input type ="hidden" name ="user" value ="<?php echo $user["UserID"]?>">
						<input type="submit" name="friends" value="Friends">
				</form>
        <?php $friends1 = "SELECT * FROM friendship f
                           WHERE (f.User1ID = '{$_SESSION["UserID"]}' AND f.User2ID = '{$_GET['id']}')";
        $friendship1 = mysqli_query($conn, $friends1);
        confirm_query($friendship1);
        $friend1 = mysqli_fetch_assoc($friendship1);
        $inviter = ($friend1["User1ID"] == "") ? 0 : 1;
        if ($inviter) {
            if ($friend1["Status"] == 'Accepted') {
                $isfriend = 1;
            } else {
                $isfriend = 0;
            }
        }
        if ($inviter && !$isfriend) { ?>

        <form action="user_profile.php" method="get">
          <input type ="hidden" name ="id" value ="<?php echo $user["UserID"]?>">
          <input type="submit" name="cancel" value="Cancel Request">
				</form>
        <?php } elseif ($inviter && $isfriend) {?>
        <form action="user_profile.php" method="get">
            <input type ="hidden" name ="id" value ="<?php echo $user["UserID"]?>">
            <input type="submit" name="unfriend" value="Unfriend">

				</form>
        <?php } else {
            $friends2 = "SELECT * FROM friendship f
                         WHERE (f.User2ID = '{$_SESSION["UserID"]}' AND f.User1ID = '{$_GET['id']}')";
            $friendship2 = mysqli_query($conn, $friends2);
            confirm_query($friendship2);
            $friend2 = mysqli_fetch_assoc($friendship2);
            $invited = ($friend2["User1ID"] == "") ? 0 : 1;
            if ($invited) {
                if ($friend2["Status"] == 'Accepted') {
                    $isfriend = 1;
                } else {
                    $isfriend = 0;
                }
            }
            if ($invited && !$isfriend) { ?>
            <form action="user_profile.php" method="get">
                <input type ="hidden" name ="id" value ="<?php echo $user["UserID"]?>">
				        <input type="submit" name="accept" value="Accept">
                <input type="submit" name="decline" value="Decline">  
				    </form>
            <?php } elseif ($invited && $isfriend) {?>
            <form action="user_profile.php" method="get">
                <input type ="hidden" name ="id" value ="<?php echo $user["UserID"]?>">
                <input type="submit" name="unfriend" value="Unfriend">
				    </form>
            <?php } else { ?>
            <form action="user_profile.php" method="get">
                <input type ="hidden" name ="id" value ="<?php echo $user["UserID"]?>">
				        <input type="submit" name="addfriend" value="Add Friend">
				    </form>
            <?php }
        } ?>
        </div>
        <div class="col-sm-8">
        Date of Birth: <?php echo $user["DateOfBirth"]?> <br /> <br />
        Location: <?php echo $user["CurrentLocation"]?><br/><br/>
        Email: <?php echo $user["Email"]?><br/><br/>
        Phone Number: <?php echo $user["PhoneNumber"]?><br/><br/>
        </div>
        </div>
        <hr />
        <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
