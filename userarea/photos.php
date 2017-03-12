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

        <h2>Photos</h2>
        <div>
            <a href="collections.php">Back to Collections</a>
        </div>
        <div>
            <button type="button" class="btn"  data-toggle="modal" data-target="#addPhoto">Add photos to collection</button>
            <button class="btn" onclick="$('.picdelete').toggleClass('hidden');">Delete Photos from collection</button>
            <form method="post" style="display: inline;">
                <button type="submit" name="delete_all" value="<?php echo $_GET['collection'] ?>" class="btn btn-danger picdelete hidden" onclick="return confirm('Are you sure you want to delete all photos from this collection?')">Delete all photos from this collection</button>
            </form>
            <?php echo message();?>
            <!-- Modal -->
            <div id="addPhoto" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add new collection</h4>
                        </div>
                        <div class="modal-body">
                            <h4>Enter collection details:</h4>
                            <form action="" method="post" enctype="multipart/form-data">
                                Select image to upload:
                                <input type="file" name="fileToUpload" id="fileToUpload">
                                <input type="text" name="collectionid" value="<?php echo "{$_GET['collection']}"?>" class="hidden" readonly>                                    
                                Caption: <input type="text" name="caption" placeholder="(optional)"></input><br />
                                Access Rights: <?php print_access_selector(); ?><br />
                                <input type="submit" value="Upload Image" name="submit">
                            </form>
                        </div>
                        <!--<div class="modal-footer">
                            <button id="add_collection" type="submit" class="btn" name="add" value="add" data-dismiss="modal">Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>-->
                    </div>
                </div>
            </div>  
        </div><br />
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
                <div class="col-md-3">
                <figure>
                    <a href="single_photo.php?<?php echo ("collection=" . $collection_id . "&" . $photo_details)?>">
                        <img class="img-responsive" src="img/<?php echo ($collection_id . "/" . $photo["FileSource"])?>" alt="Collection photo">
                    </a>
                    <figcaption>
                        <form method="post">
                                <input type="text" name="photo_src" value="<?php echo $photo['FileSource'] ?>" class="hidden" readonly>
                                <button class="btn btn-danger picdelete hidden" type="submit" name="delete_photo" value="<?php echo $photo['PhotoID']?>" style="width: 100%">
                                        <span class="glyphicon glyphicon-trash">
                                </button>
                        </form>
                    </figcaption>
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
