<?php
session_start(); 
?>
<!DOCTYPE html>
<html>
	<head>
	<title>Bindr Log In Page</title>
	</head>
	<body>
		<header>
			<h1>
                Login
            </h1>
		</header>
	<main>
		<nav>
			
		</nav>
		<div class="login-page">
		<div class="form">
			<form action ="login.php" method="POST">
			<p>Please Enter Details to Log In</p>
				<input type="text" placeholder="Username" name="uname" >
				<input type="password" placeholder="Password" name="psw" >
				<input type="submit" name="sub" value="Log In">
				<p class="message">Not Registered?<a href="register.php"> Register</a></p>
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
								$query1 = 'SELECT user_name, password FROM customers WHERE user_name = :username AND password = :password;';
								$statement = $myDBconnection -> prepare($query1);
								$statement -> bindValue(':username', $uname); 
								$statement -> bindValue(':password', $psw);
								$statement -> execute();
								$rows = $statement -> fetch();
							} catch (PDOException $e) {
								$error_message = $e->getMessage();
								echo "<p>An error occurred while trying to add data from the table: $error_message </p>";
							}
							print_r($rows);
							if ($rows['user_name'] == $uname){
								echo "you are a user!";
								$_SESSION["myShop"] = $uname;
								header('Location: index.php');
							} else {
								echo "You are NOT a user!";
								session_unset($_SESSION["myShop"]); 
								session_destroy(); 
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
	</main>
	</body>
</html>