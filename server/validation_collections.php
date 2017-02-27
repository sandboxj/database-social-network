<?php require_once("../server/validation_functions.php");?>
<?php
if (isset($_POST["add_collection"])) {
    // Check if post is blank
    if (strlen(trim($_POST["title"]))) {
        // Gather content 
        $collection_title = mysqli_real_escape_string($conn, $_POST["title"]);
        $create_time = date('Y-m-d H:i:s');
        $userid = $_SESSION["UserID"];
        $collection_id = $collection_title . $userid;
        // Enter post into DB
        
        $query = "INSERT INTO Photo_collection (CollectionID, UserID, DateCreated, CollectionTitle) ";
        $query .= "VALUES ('{$collection_id}', '{$userid}','{$create_time}', '{$collection_title}')";
        $result = mysqli_query($conn, $query);
        $_SESSION["message"] = ($result) ? "" : "Adding new collection failed. No duplicate album titles allowed.";
        // redirect_to("../userarea/collections.php");
    } 
    else {
        $_SESSION["message"] = "Adding new collection failed. Title cannot be empty.";
        // redirect_to("../userarea/collections.php");
    }
} else {
    // Do nothing
}

