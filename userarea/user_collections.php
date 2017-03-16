<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php require_once("../server/functions_blog.php");?>
<?php require_once("../server/functions_circle.php");?>
<?php $visited_user = find_user_by_id($_GET["id"]); ?>
<?php $page_title= "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Collections"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<section class="jumbotron jumbotron-photocollections" >
    <div class="container">
        <div class="row">
            <div class="col-md-3 content">
                <?php include("user_navbar.php"); ?>
            </div>
            <div class="col-md-1">

            </div>
            <div class="col-md-4 text-center">
                <h2><?php echo "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Collections'";?></h2>
            </div>
        </div>
    </div>
</section>

    <div class="container ">
        <?php
            $viewer_userID = $_SESSION['UserID'];
            $user_collections = find_collections($visited_user["UserID"]);
            $count = 0;
        while ($collection = mysqli_fetch_assoc($user_collections)) {
            $access_rights = $collection["AccessRights"];
            $in_circle = is_in_another_user_circle($visited_user["UserID"], $viewer_userID);
            $is_friend = check_friendship($visited_user["UserID"], $viewer_userID);
            $is_friend_of_friend = ($is_friend) ? true : check_friends_of_friends($visited_user["UserID"], $viewer_userID);
            $check = confirm_access_rights($access_rights, $is_friend, $in_circle, $is_friend_of_friend);
            if(!$check) {
                continue;
            }
                if (($count == 0)) {
                    echo "<div class='row'>";
                } else {
                }
        ?>


        <div class='polaroid col-md-3 text-center '>

            <a class="photo-collection-anchor" href='user_photos.php?id=<?php echo $visited_user["UserID"] ?>&collection=<?php echo $collection["CollectionID"] ?>'>
            <figure>
                <?php 
                    $newest_photo = newest_photo_src($collection["CollectionID"]);
                    $newest_src = (isset($newest_photo)) ? "img/" . $collection["CollectionID"] . "/" . $newest_photo["FileSource"] : "img/empty.png";
                ?>

                <img  src="<?php echo $newest_src; ?>" alt='thumbnail' class="center-block img-responsive"><br />


                <p><?php echo $collection["CollectionTitle"] ?></p>

            </figure>
            </a>
        </div>
        <?php
				if (($count == 3)) {
					echo "</div>";
					$count = 0;
				} else {
					$count += 1;
				}
            }
        
        ?>
        <?php
            mysqli_free_result($user_collections);
        ?>
    </div></div>
        

<?php include("../includes/footer.php"); ?>
