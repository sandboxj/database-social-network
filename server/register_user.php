<?php require_once 'db_connection.php';

	$query="SELECT * FROM User";
	$result = mysqli_query($conn, $query);
	// Test if there was a query error
	if(!$result) {
		die("Database query failed");
	}
	while($row = mysqli_fetch_assoc($result)) {
		var_dump($row);
		echo "<hr />";
	}
	echo "success";
	mysqli_free_result($result);
	// $arr = array();
	// if($result->num_rows > 0) {
	// 	while($row = $result->fetch_assoc()) {
	// 		$arr[] = $row;
	// 	}
	// }

	// echo $json_response = json_encode($arr); //JSON-encode the response!

?>
<?php
	mysqli_close($conn);
?>
