<?php
session_start(); 
?>
<!DOCTYPE html>
<html>
	<head>
	<title>Registration</title>
		<link rel="stylesheet" type="text/css" href="Hodges_styles.css" />
	</head>
	<body>
		<header>
			<?php include "header.php"; ?>
		</header>
	<main>
		<nav>
			<?php include "nav.php"; ?>	
		</nav>
		
		<div class="login-page">
		<div class="form">	
			
			<form action ="register.php" method="POST">
			<p>Please Enter Details to Register</p>
				<input type="text" placeholder="Username" name="uname" >
				<input type="password" placeholder="Password" name="psw" >
				<input type="submit" name="sub" value="Register">
			</form>
		</div>
		</div>
		<?php 
			try {
				$DSN = "mysql:host=$HOST_NAME;dbname=$DATABASE_NAME";
				$myDBconnection = new PDO($DSN, $USERNAME, $PASSWORD);
			}
			catch (PDOException $e) {
				$error_message = $e -> getMessage(); 
				echo $error_message . "<br>";
			}	
						
			//sanitize function 
			function sanitize($bad){
				$bad = stripslashes($bad);
				$bad = strip_tags($bad);
				$good = htmlentities($bad);
				return $good;
			}
			
			//is form submitted?
			if(isset($_POST["sub"])){ 
			
				//are all the fields filled out?
				if( !(empty($_POST["uname"])) && !(empty($_POST["psw"]))) {
					
						//sanitize each of the fields (send each field to the sanitize function)
						$uname = sanitize($_POST["uname"]);
						$psw = sanitize($_POST["psw"]);
						
						//do all the sanitized variables have a value?
						if( $uname != "" && $psw != "") {
							//try to insert the information into the database
							try {
								//check to see if your table has the same fields & is spelled the same way
								$query1 = 'SELECT user_name FROM customers WHERE user_name = :username;';
								$statement = $myDBconnection -> prepare($query1);
								$statement -> bindValue(':username', $uname); 
								//$statement -> bindValue(':password', $psw);
								$statement -> execute();
								$rows = $statement -> fetch();
							} catch (PDOException $e) {
								$error_message = $e->getMessage();
								echo "<p>An error occurred while trying to add data from the table: $error_message </p>";
							}
							//print_r($rows);
							if ($rows['user_name'] == $uname){
								echo "$uname is already taken.";
							} else {
								try {
								$dcr=date("Y-m-d");
								//	echo $dcr;
								//INSERT new data to table that has the same fields & is spelled the same way
								$query2 = 'INSERT INTO customers (user_name, password, date_created) VALUES (:username, :password, :date_created);';
								$statement2 = $myDBconnection -> prepare($query2);
								$statement2 -> bindValue(':username', $uname); 
								$statement2 -> bindValue(':password', $psw);
								$statement2 -> bindValue(':date_created', $dcr);
								$statement2 -> execute();
								
								echo "You are now a user!";	
								
								//$rows = $statement -> fetch();
							} catch (PDOException $e) {
								$error_message = $e->getMessage();
								echo "<p>An error occurred while trying to add data from the table: $error_message </p>";
							}
							}
						} else { //not all sanitized variables have values
							echo "Bad data was inserted into the fields.";
						}	
					
				} else { //not all fields were filled in
					echo "Not all fields were filled in.";
				}
			} else { //form not submitted
				echo "Form has not been submitted yet.";
			}
		?>
		<footer>
			<?php include "footer.php"; ?>
		</footer>
	</main>
	</body>
</html>