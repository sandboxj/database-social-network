<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_upload.php");?>
<?php require_once("../server/validation_photos.php");?>
<?php $page_title="Photos"?>
<?php confirm_logged_in(); ?>
<?php exist_collection($_GET["collection"]);?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>
<?php $collection_details = find_collection($_GET["collection"]); ?>



<section class="jumbotron jumbotron-photocollections">

    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 ">
                <a  style="color: white;" href="collections.php">
                <button class="btn btn-primary pull-left"><i class="glyphicon glyphicon-chevron-left"></i> Back to Collections</button>
                </a>
            </div>
            <div class="col-md-4">
                <h1><?php echo $collection_details["CollectionTitle"] ?></h1>
                <?php echo message()?>
            </div>
            <div class="col-md-4">
                <div class="btn-toolbar pull-right" role="toolbar" aria-label="blog_options">
                    <div class="btn-group-vertical" aria-label="blog_options">
                        <button class="btn btn-default" data-toggle="modal" data-target="#changePrivacy"> <span class="glyphicon glyphicon-cog"></span> Access Rights</button>
                        <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#addPhoto"><i class="glyphicon glyphicon-plus"></i> Add photos to collection</button>
                        <button class="btn btn-danger" onclick="$('.picdelete').toggleClass('hidden');"><i class="glyphicon glyphicon-trash"></i> Delete Photos from collection</button>
                        <br>
                        <br>
                        <br>
                        <form method="post" style="display: inline;">
                            <button type="submit"  name="delete_all" value="<?php echo $_GET['collection'] ?>" class="btn btn-danger picdelete hidden" onclick="return confirm('Are you sure you want to delete all photos from this collection?')"><i class="glyphicon glyphicon-trash"></i> Delete all photos from this collection</button>
                            <br>
                            <br>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Modal -->
<div id="changePrivacy" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change collection visibility - Current setting: <?php echo convert_access_rights_to_string($collection_details["AccessRights"]) ?></h4>
            </div>
            <div class="modal-body">


                <form action="" method="post">

                    <input id="collection-title" type="text" name="collectionid" value="<?php echo "{$_GET['collection']}"?>" class="hidden" readonly>
                    <label for="collection-access">Access Rights:</label>
                    <div class="form-group" id="collection-access">
                        <?php print_access_selector()?>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Save changes" name="changePrivacy">
                </form>
            </div>
            <!--<div class="modal-footer">
                <button id="add_collection" type="submit" class="btn" name="add" value="add" data-dismiss="modal">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>-->
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addPhoto" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add a photo to this collection:</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="caption">Caption:</label>
                        <input class="form-control" type="text" name="caption" id="caption" placeholder="(optional)"></input><br />

                        <input class="form-control" type="file" class ="btn btn-default" name="fileToUpload" id="fileToUpload">
                        <input type="text" name="collectionid" value="<?php echo "{$_GET['collection']}"?>" class="hidden form-control" readonly>

                        <input class="form-control btn btn-primary" type="submit" value="Upload Image" name="submit">
                    </div>
                </form>
            </div>
            <!--<div class="modal-footer">
                <button id="add_collection" type="submit" class="btn" name="add" value="add" data-dismiss="modal">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>-->
        </div>
    </div>
</div>


<br />


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



        <a href="single_photo.php?<?php echo ("collection=" . $collection_id . "&" . $photo_details)?>">
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
