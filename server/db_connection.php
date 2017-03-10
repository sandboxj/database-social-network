<?php
// ------> LOCAL database connection:
$servername='localhost';
$username='root';
$password='root';
$database='soshallnetwork';
$conn= New mysqli($servername,$username,$password,$database);

// ------> Azure Database connection

defined("DB_SERVER") ? null : define("DB_SERVER", "eu-cdbr-azure-west-d.cloudapp.net");
defined("DB_USER") ? null : define("DB_USER", "bfd14cc083b66f");
defined("DB_PASS") ? null : define("DB_PASS", "77230adb");
defined("DB_NAME") ? null : define("DB_NAME", "soshallnetwork");

$conn=New mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);


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
