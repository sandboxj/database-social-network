<?php

//1. ------> LOCAL database connection:
$servername='localhost:3306';
$username='group24';
$password='group24';
$database='socialnetwork_db';
$conn=New mysqli($servername,$username,$password,$database);

//Connection error handling:

// echo @mysqli_ping($conn) ? 'true' : 'false';
if(mysqli_connect_errno()) {
  die("Database connection failed: " .
      mysqli_connect_error() .
      " (" . mysqli_connect_errno() . ")"
  );
} else {
  // echo "Connected";
}

?>
