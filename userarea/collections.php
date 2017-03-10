<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_collections.php");?>
<?php $page_title="Photo Collections"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

        <h2>Your Photo Collections</h2>
        <button type="button" class="btn"  data-toggle="modal" data-target="#addCollection">Add new collection</button>
        <button class="btn" onclick="$('.coldelete').toggleClass('hidden');">Delete Collection</button>
        <?php echo message()?>
        <!-- Modal -->
        <div id="addCollection" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add new collection</h4>
                    </div>
                    <div class="modal-body">
                        <p>Enter collection details:</p>
                        <form action="collections.php" method="post">
                            Title: <input type="text" name="title"></input><br />
                            Access Rights: <?php print_access_selector(); ?>                            
                            <br />
                            <input class="btn" type="submit" name="add_collection" value="Add" />
                        </form>
                    </div>
                    <!--<div class="modal-footer">
                        <button id="add_collection" type="submit" class="btn" name="add" value="add" data-dismiss="modal">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>-->
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
        <?php
            $user_collections = find_collections($_SESSION["UserID"]);
        	while ($collection = mysqli_fetch_assoc($user_collections)) {
        ?>
            <div class='polaroid col-md-3'>
                <figure>
                    <img src='' alt='thumbnail'><br />
                    <figcaption>
                    <a href='photos.php?collection=<?php echo $collection["CollectionID"] ?>'>
                        <?php echo $collection["CollectionTitle"] ?> 
                    </a>
                    <!--Do not display delete button for profile picture collection-->
                    <?php if(!($collection["CollectionID"]==("Profilepictures" . $_SESSION["UserID"]))) {?>
                    <form method="post">
                            <button class="btn btn-danger coldelete hidden" type="submit" name="delete_collection" value="<?php echo $collection["CollectionID"] ?>" style="width: 100%">
                                    <span class="glyphicon glyphicon-trash">
                            </button>
                    </form>
                    <?php } ?>
                    </figcaption>
                </figure>
            </div>
        <?php
            }
        ?>
        <?php
            mysqli_free_result($user_collections);
        ?>
        </div>
        
        <hr />
        <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
