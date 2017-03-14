<?php require_once("../server/validation_functions.php");?>
<?php
if (isset($_POST["search_result"])) {
    // Check if post is blank
    if (strlen(trim($_POST["search_query"]))) {
        // Checks for special chars
        if(!preg_match('/[^A-Za-z0-9\s]/', $_POST["search_query"])) {
            $search_query = regex_clean($_POST["search_query"]);
            $safe_search = mysqli_real_escape_string($conn, $search_query);        

            // Search DB
            $query = "SELECT * FROM user ";
            $query .= "WHERE ((FirstName REGEXP '{$safe_search}' AND NOT UserID = '{$_SESSION["UserID"]}') ";
            $query .= "OR (LastName REGEXP '{$safe_search}' AND NOT UserID = '{$_SESSION["UserID"]}'))";
            $query .= "AND user.PrivacySetting NOT LIKE '3'";
            $result = mysqli_query($conn, $query);
            confirm_query($result);
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
