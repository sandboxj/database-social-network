<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_upload.php");?>
<?php require_once("../server/validation_photos.php");?>
<?php $visited_user = find_user_by_id($_GET["id"]); ?>
<?php exist_collection($_GET["collection"]);?>
<?php $page_title= "{$visited_user["FirstName"]} {$visited_user["LastName"]}'s Photos"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

        <h2>Photos</h2>
        <?php include("user_navbar.php"); ?><br />
        <div>
            <a href="user_collections.php?id=<?php echo $_GET["id"] ?>">Back to Collections</a>
        </div>
        <div>
        <div class="container">
            <?php
                $collection_id = $_GET["collection"];
                $collection_photos = find_photos_from_collection($collection_id);
                $count = 0;

                while ($photo = mysqli_fetch_assoc($collection_photos)) {
                    $photo_details = http_build_query($photo);
                    if(($count == 0)) {
                        echo "<div class='row'>"; 
                    } else {}
            ?>                        
                <div class="polaroid col-md-3">
                <figure>
                    <a href="single_photo.php?id=<?php echo $_GET['id'];?>&<?php echo ("collection=" . $collection_id . "&" . $photo_details)?>">
                        <img class="img-responsive" src="img/<?php echo ($collection_id . "/" . $photo["FileSource"])?>" alt="Collection photo">
                    </a>
                <figure>
                </div>
                <?php 
                    if(($count == 3)) {
                        echo "</div>"; 
                        $count = 0;
                    } else {
                        $count += 1;
                    }
                ?>
            <?php
                }
                mysqli_free_result($collection_photos);
            ?>
        </div></div>

        <hr />
        <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
