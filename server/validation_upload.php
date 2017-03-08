<?php require_once("../server/validation_functions.php"); ?>
<?php
if (isset($_POST["submit"])) {
    // Check if any file has been selected
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $collectionid = $_POST["collectionid"]; 
        $target_dir = "img/" . $collectionid . "/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        $_SESSION["message"] = "";
        // Check if image file is a actual image or fake image
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION["message"] .= "File is not an image.";
            $uploadOk = 0;
        }
    // Check if file already exists
        if (file_exists($target_file)) {
            unlink($target_file);
            // $uploadOk = 0;
        }

// Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $_SESSION["message"] .= "Sorry, your file is too large (max 5 mb).";
            $uploadOk = 0;
        }

// Allow certain file formats
        $imageFileType = strtolower($imageFileType);
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $_SESSION["message"] .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION["message"] .= "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            $temp_ext = explode(".", $_FILES["fileToUpload"]["name"]);
            $userid = $_SESSION["UserID"];
            $date_posted = date('Y-m-d H:i:s');           
            $new_filename = $collectionid . rawurlencode($date_posted) . "." . end($temp_ext);
            $new_filename = str_replace("%", "-", $new_filename);
            $access_rights = isset($_POST["access"]) ? $_POST["access"] : "1";
            $caption = isset($_POST["caption"]) ? mysqli_real_escape_string($conn, $_POST["caption"]) : null;

            // Check if profile album exists
            $query = "SELECT * FROM Photo_Collection ";
            $query .= "WHERE CollectionID = '{$collectionid}';";
            $result = mysqli_query($conn, $query);
            confirm_query($result);
            // If album doesnt exist, create it
            if (mysqli_num_rows($result)<1) {
                // in Filesystem
                $dir = "img/" . $collectionid;
                if(!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                } else {}

                // in DB
                mysqli_free_result($result);
                $query = "INSERT INTO Photo_Collection (CollectionID, Caption, UserID, DateCreated, CollectionTitle) VALUES (";
                $query .= "'{$collectionid}', '{$caption}', '{$userid}', '{$date_posted}', 'Profile pictures'";
                $query .= ") ";
                $result = mysqli_query($conn, $query);
            } else {
                mysqli_free_result($result);
            }
          
                // Insert into photos
                $query = "INSERT INTO Photo (CollectionID, DatePosted, AccessRights, FileSource) ";
                $query .= "VALUES (";
                $query .= "'{$collectionid}', '{$date_posted}', '{$access_rights}', '{$new_filename}');";
                $result = mysqli_query($conn, $query);
                $new_id = mysqli_insert_id($conn);

                // If profile picture, update profile picture id
                if($collectionid===("Profilepictures" . $userid)) {
                    $query = "Update User ";
                    $query .= "SET ProfilePhotoID = '{$new_id}' ";
                    $query .= "WHERE UserID = '{$userid}' ";
                    $result = mysqli_query($conn, $query);
                } else {}
                
            if ($result) {
                    // Store in directory
                    if(is_dir($target_dir)) {
                        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $new_filename);
                        $_SESSION["message"] .= "Upload successful.";
                    } else {
                        $_SESSION["message"] .= "Internal error. Sorry, there was an error uploading your file.";
                    }
            } else {
                // Failure
                $_SESSION["message"] .= "Sorry, there was an error uploading your file.";
            }
                    
                // Deleted test if storing work. Reinstate?
                // if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // }
                // else {
                //     $_SESSION["message"] .= "Sorry, there was an error uploading your file.";
                // }
        }
    } else {
        $_SESSION["message"] .= "Sorry, you have not selected any file to upload.";
    }
    redirect_to("");
}
