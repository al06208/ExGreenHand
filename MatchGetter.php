<?php 
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "distwebproject";

	//connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	//check for errors
	if($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	}

	$q = $_REQUEST["q"];
	$sql = "SELECT * FROM matches WHERE UserId = " . $q . " ORDER BY JobId LIMIT 1";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		$row = $result -> fetch_assoc();
		$sqljob = "SELECT * FROM jobs WHERE JobId = " . $row["JobId"];
		$job = $conn->query($sqljob);
		if($job -> num_rows > 0){
			$job = $job -> fetch_assoc();
			$sqlEmployer = "SELECT * FROM employer WHERE UserId = " . $job["UserId"];
			$employer = $conn -> query($sqlEmployer);
			if($employer -> num_rows > 0){
				$employer = $employer -> fetch_assoc();
				echo "<h1>" . $job["Jobs"] . "</h1><h2>" . $job["ExperienceSeeking"] . "</h2><h3>" . $employer["EmployeeName"] . "</h3><p>Salary: $" . $job["Salary"] . "</p>";
			}
			
		}
		else echo "<p>couldn't find the job</p>";
	}
	else echo "<p>couldn't find a match</p>";

?>