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
<?php $collection_details = find_collection($_GET["collection"]); ?>



<section class="jumbotron jumbotron-photocollections">

    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 ">
                <a  style="color: white;" href="user_collections.php?id=<?php echo $visited_user['UserID']; ?>">
                <button class="btn btn-primary pull-left"><i class="glyphicon glyphicon-chevron-left"></i> Back to Collections</button>
                </a>
                <br>
                <br>
                <br>
                <?php include("user_navbar.php"); ?>
            </div>
            <div class="col-md-4">
                <h1><?php echo $collection_details["CollectionTitle"] ?></h1>
                <?php echo message()?>
            </div>
            <div class="col-md-4">

    </div>
        </div>
    </div>
</section>


<div class="container">
    <div class="row">
        <div col-md-12>




            <?php
            $collection_id = $_GET["collection"];
            $collection_photos = find_photos_from_collection($collection_id);

            $count=0;
            while ($photo = mysqli_fetch_assoc($collection_photos)) {
                $photo_details = http_build_query($photo);
                $caption = $photo['Caption'];


                ?>



                <a href="single_photo.php?<?php echo ("collection=" . $collection_id . "&" . $photo_details)?>&id=<?php echo $_GET['id'];?>">
                    <div class="polaroid col-md-3">

                        <img class="img-responsive" src="img/<?php echo ($collection_id . "/" . $photo["FileSource"])?>" alt="Collection photo">

                        <figcaption>
                            <p><?php echo "Caption: ".$caption; ?></p>
                            <form method="post">
                                <input type="text" name="photo_src" value="<?php echo $photo['FileSource'] ?>" class="hidden" readonly>
                                <button class="btn btn-danger picdelete hidden" type="submit" name="delete_photo" value="<?php echo $photo['PhotoID']?>" style="width: 100%">
                                        <span class="glyphicon glyphicon-trash">
                                </button>
                            </form>
                        </figcaption>
                        <figure>
                    </div>
                </a>

                <?php
            }
            mysqli_free_result($collection_photos);
            ?>
        </div>
    </div>

</div>


<?php include("../includes/footer.php"); ?>
