<?php require_once("../server/validation_functions.php");?>
<?php
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (isset($_POST["delete_photo"])) {
    $id_todelete = $_POST["delete_photo"];
    $userid = $_SESSION["UserID"];
    $collectionid = $_GET['collection'];

    // Delete from Filesystem
    $src_todelete = "img/" . $_GET["collection"] . "/" . $_POST["photo_src"];
    if (file_exists($src_todelete)) {
        if (!unlink($src_todelete)) {
            $_SESSION["message"] = ($result) ? "" : "Deleting photo failed.";
        } else { }
    } else { }
    // Delete from DB
    $query = "DELETE FROM photo ";
    $query .= "WHERE PhotoID = '{$id_todelete}'";
    $result = mysqli_query($conn, $query);
    $_SESSION["message"] = ($result) ? "" : "Deleting photo failed.";

    // If deleted picture is profile picture in use, set previous picture as profile
    // If no previous profile pig, set default profile pic
    if($_GET["collection"]===("Profilepictures" . $userid)) {
            $query2 = "CALL if_profile_pic({$id_todelete}, '{$collectionid}')";
            $result2 = mysqli_query($conn, $query2);
            $_SESSION["message"] .= ($result2) ? "" : "Photo deleted but changing profile picture failed.";
    } else {}
    redirect_to("{$actual_link}");
} 
elseif (isset($_POST["delete_all"])) {
    $id_todelete = $_POST["delete_all"];
    $userid = $_SESSION["UserID"];
    // Delete from filesystem
    $files = glob("img/" . $_GET["collection"] . "/*"); // get all file names
    foreach($files as $file) { 
        if(is_file($file))
            unlink($file); 
    }
    // Delete from DB
    $query = "DELETE FROM photo ";
    $query .= "WHERE CollectionID = '{$id_todelete}'";
    $result = mysqli_query($conn, $query);
    $_SESSION["message"] = ($result) ? "" : "Deleting photos failed.";

    // If deleted album is profile picture, set default pic as profile pic
    if($_GET["collection"]===("Profilepictures" . $userid)) {
        $query = "UPDATE User ";
        $query .= "SET ProfilePhotoId = 1 ";
        $query .= "WHERE UserID = '{$userid}'";
        $result = mysqli_query($conn, $query);
        $_SESSION["message"] .= ($result) ? "" : "Photos deleted but setting default profile picture failed.";
    } else {}
    redirect_to("{$actual_link}");
} elseif (isset($_POST["changePrivacy"])) {
    $id_toupdate = $_POST["changePrivacy"];
    $collection_update = $_POST["collectionid"];
    $new_setting = $_POST["access"];
    $query = "UPDATE photo_collection ";
    $query .= "SET AccessRights = {$new_setting} ";
    $query .= "WHERE CollectionID = '{$collection_update}'";
    $result = mysqli_query($conn, $query);
    $_SESSION["message"] = ($result) ? "" : "Settings updated";
}
else {
    // Do nothing
}
