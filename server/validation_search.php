<?php require_once("../server/validation_functions.php");?>
<?php
if (isset($_POST["search_result"])) {
    // Check if post is blank
    if (strlen(trim($_POST["search_result"]))) {
        // Checks for special chars
        if(!preg_match('/[^A-Za-z0-9\s]/', $_POST["search_result"])) {
            $search_query = regex_clean($_POST["search_result"]);
            $safe_search = mysqli_real_escape_string($conn, $search_query);        

            // Search DB
            $query = "SELECT * FROM user ";
            $query .= "WHERE ((FirstName REGEXP '{$safe_search}' AND NOT UserID = '{$_SESSION["UserID"]}') ";
            $query .= "OR (LastName REGEXP '{$safe_search}' AND NOT UserID = '{$_SESSION["UserID"]}'))";
            $query .= "AND user.PrivacySetting != '3'";
            $result = mysqli_query($conn, $query);
            confirm_query($result);
            
            $query2 = "SELECT * FROM blog b ";
            $query2 .= "WHERE Title REGEXP '{$safe_search}' AND NOT UserID = '{$_SESSION["UserID"]}' ";
            $query2 .= "AND b.AccessRights != '0'";
            $result2 = mysqli_query($conn, $query2);
            confirm_query($result2);
        } else {
            $result = null;
            $_SESSION["message"] = "No special characters allowed in search.";
        }
    } else {
        $result = null;
        $_SESSION["message"] = "Query cannot be empty. Please enter a search query.";
    }
} else {
    // Do nothing
}
