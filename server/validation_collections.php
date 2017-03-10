<?php require_once("../server/validation_functions.php");?>
<?php
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (isset($_POST["add_collection"])) {
    // Check if post is blank
    if (strlen(trim($_POST["title"]))) {
        // Gather content 
        $collection_title = mysqli_real_escape_string($conn, $_POST["title"]);
        $collection_access = $_POST["access"];
        $create_time = date('Y-m-d H:i:s');
        $userid = $_SESSION["UserID"];
        $collection_id = trim($collection_title) . $userid;

        // Create collection folder
        $dir = "img/" . $collection_id;
        if(!file_exists($dir)) {
            mkdir($dir, 0755, true);
        } else {}

        // Enter post into DB       
        $query = "INSERT INTO Photo_collection (CollectionID, UserID, DateCreated, CollectionTitle, AccessRights) ";
        $query .= "VALUES ('{$collection_id}', '{$userid}','{$create_time}', '{$collection_title}', '{$collection_access}')";
        $result = mysqli_query($conn, $query);
        $_SESSION["message"] = ($result) ? "" : "Adding new collection failed. No duplicate album titles allowed.";
    } 
    else {
        $_SESSION["message"] = "Adding new collection failed. Title cannot be empty.";
    }
    redirect_to("{$actual_link}");
} 
elseif (isset($_POST["delete_collection"])) {
    $id_todelete = $_POST["delete_collection"];
    // Delete from Filesystem
    $dir = "img/" . $id_todelete;
    if (is_dir($dir)) {
        // recurse on subfolders/subitems if any
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
             RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
            rmdir($file->getRealPath());
            } else {
            unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }

    // Delete from DB
    $query = "DELETE FROM photo_collection ";
    $query .= "WHERE CollectionID = '{$id_todelete}'";
    $result = mysqli_query($conn, $query);
    $_SESSION["message"] = ($result) ? "" : "Deleting collection failed.";
    redirect_to("{$actual_link}");
}
else {
    // Do nothing
}

