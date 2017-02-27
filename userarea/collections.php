<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_collections.php");?>
<?php $page_title="Photo Collections"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>

        <h2>Your Photo Collections</h2>
        <button type="button" class="btn"  data-toggle="modal" data-target="#addCollection">Add new collection</button>
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
        <?php
            $user_collections = find_collections($_SESSION["UserID"]);
        	while ($collection = mysqli_fetch_assoc($user_collections)) {
            $output = "<div>";
            $output .= $collection["CollectionTitle"];
            $output .= "<div><br />";
            echo $output;
        }
        ?>
        <?php
            mysqli_free_result($user_collections);
        ?>
        
        <hr />
        <a href="logout.php">Logout</a>

<?php include("../includes/footer.php"); ?>
