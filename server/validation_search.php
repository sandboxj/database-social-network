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
        $query .= "WHERE (FirstName REGEXP '{$safe_search}' AND NOT UserID = '{$_SESSION["UserID"]}') ";
        $query .= "OR (LastName REGEXP '{$safe_search}' AND NOT UserID = '{$_SESSION["UserID"]}') ";
        $result = mysqli_query($conn, $query);
        confirm_query($result);
        } else {
            $result = null;
            $_SESSION["message"] = "No special characters allowed in search.";
        }

        // // // Gather content 
        // // $search_query = mysqli_real_escape_string($conn, $_POST["search_query"]);
        // // $search_array = explode(' ', $search_query, 2);
        
        // // // Search DB        
        // // $query = "SELECT * FROM user u
        // --           WHERE (u.FirstName LIKE '{$search_array[0]}'
        // --           OR u.LastName LIKE '{$search_array[0]}')
        // --           AND u.UserID NOT LIKE '{$_SESSION["UserID"]}'";
        // // $result = mysqli_query($conn, $query);
        // // confirm_query($result);
        
        // // if (!$result) {
        // //     mysqli_free_result($result);
        // //     $query = "SELECT * FROM user u
        // --               WHERE (u.LastName LIKE '%{$search_array[0]} %'
        // --               OR u.LastName LIKE '% {$search_array[0]}%')
        // --               AND u.UserID NOT LIKE '{$_SESSION["UserID"]}'";
        // //     $result = mysqli_query($conn, $query);
        // //     confirm_query($result);
            
        // //     if (!$result) {
        // //         $result = null;
        // //     }
        // // }
        // // if (!$result) {
        // //     $_SESSION["message"] = "Your search query did not produce any results.";
        // // }
    } else {
        $result = null;
        $_SESSION["message"] = "Query cannot be empty. Please enter a search query.";
    }
} else {
    // Do nothing
}
