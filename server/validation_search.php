<?php require_once("../server/validation_functions.php");?>
<?php
if (isset($_POST["search_result"])) {
    // Check if post is blank
    if (strlen(trim($_POST["search_query"]))) {
        // Gather content 
        $search_query = mysqli_real_escape_string($conn, $_POST["search_query"]);
        $search_array = explode(' ', $search_query, 2);
        
        // Search DB        
        $query = "SELECT * FROM user u
                  WHERE (u.FirstName LIKE '{$search_array[0]}'
                  OR u.LastName LIKE '{$search_array[0]}')
                  AND u.UserID NOT LIKE '{$_SESSION["UserID"]}'";
        $result = mysqli_query($conn, $query);
        confirm_query($result);
        
        if (!$result) {
            mysqli_free_result($result);
            $query = "SELECT * FROM user u
                      WHERE (u.LastName LIKE '%{$search_array[0]} %'
                      OR u.LastName LIKE '% {$search_array[0]}%')
                      AND u.UserID NOT LIKE '{$_SESSION["UserID"]}'";
            $result = mysqli_query($conn, $query);
            confirm_query($result);
            
            if (!$result) {
                $result = null;
            }
        }
        if (!$result) {
            $_SESSION["message"] = "Your search query did not produce any results.";
        }
    } else {
        $_SESSION["message"] = "Please enter a search query.";
    }
} else {
    // Do nothing
}
