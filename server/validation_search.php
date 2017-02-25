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
                  WHERE u.FirstName like '{$search_array[0]}'
                  OR u.LastName like '{$search_array[1]}'";
        $result = mysqli_query($conn, $query);
        confirm_query($result);
        
        if (!$user = mysqli_fetch_assoc($result)) {
            mysqli_free_result($result);
            $query = "SELECT * FROM user u
                      WHERE u.LastName like '%{$search_array[0]} %'
                      OR u.LastName like '% {$search_array[0]}%'";
            $result = mysqli_query($conn, $query);
            confirm_query($result);
            
            if (!$user_set = mysqli_fetch_assoc($result)) {
                $result = null;
            }
        }
        
        if ($result) {
            redirect_to("../userarea/search.php");
            while($user = mysqli_fetch_assoc($result)) { 
                $output = "Author: " . $user["FirstName"] . " " . $user["LastName"];
		            $output .= " , " . $user["DatePosted"] . "<br />";
	              $output .= $user["Content"] . "<br />";
                echo $output;
            }
            mysqli_free_result($result);
        } else {
            $_SESSION["message"] = "Your search query did not produce any results.";
            redirect_to("../userarea/search.php");
        }
    } else {
        $_SESSION["message"] = "Please enter a search query.";
        redirect_to("../userarea/search.php");
    }
} else {
    // Do nothing
}