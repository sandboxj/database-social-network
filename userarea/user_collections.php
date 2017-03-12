<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php $visited_user = find_user_by_id($_GET["id"]); ?>
<?php $page_title= "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Collections"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

    <h2><?php echo "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Collections" ?></h2>
    <?php include("user_navbar.php"); ?><br />
    <div class="container">
        <?php
            $viewer_userID = $_SESSION['UserID'];
            $user_collections = find_collections($visited_user["UserID"]);
            $count = 0;
        while ($collection = mysqli_fetch_assoc($user_collections)) {
            $access_rights = $collection["AccessRights"];
            if ($access_rights==0) {
                continue;
            } else {
				if($access_rights==1) {
					$exist_friendship = find_friendship($viewer_userID, $visited_user["UserID"]);
					if (mysqli_num_rows($exist_friendship)<1) {
						continue;
					} else {}
				} elseif ($access_rights == 3) {
					// Check for circles
				} 

                if (($count == 0)) {
                    echo "<div class='row'>";
                } else {
                }
        ?>
        <div class='polaroid col-md-3'>
            <figure>
                <img src='' alt='thumbnail'><br />
                <figcaption>
            <a href='user_photos.php?id=<?php echo $visited_user["UserID"] ?>&collection=<?php echo $collection["CollectionID"] ?>'>
                <?php echo $collection["CollectionTitle"] ?> 
                </a>
                </figcaption>
            </figure>
        </div>
        <?php
				if (($count == 3)) {
					echo "</div>";
					$count = 0;
				} else {
					$count += 1;
				}
            }
        }
        ?>
        <?php
            mysqli_free_result($user_collections);
        ?>
    </div></div>
        
    <hr />
    <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
