<?php

session_start();

function message()
{
                
    if (isset($_SESSION["message"])) {
        $output = "<div>";
		$output .= htmlentities($_SESSION["message"]);
        $output .= "</div><br />";

		// clear message after use
		$_SESSION["message"] = null;
		
		return $output;
    }
}
?>
