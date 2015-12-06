<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Login</title>
<style>
.error {color:#FF0000;}
</style>
<?php

include "include.php";

function test_input($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

//if the user is already logged in, redirect them back to homepage
if(isset($_SESSION["username"])) {
  echo "You are already logged in. \n";
  echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
  header("refresh: 3; index.php");
}
else {
	$userErr = $passErr = "";
	$username = $password = "";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(empty($_POST["username"])) $userErr = "Username Required";
		else $username = test_input($_POST["username"]);
		
		if(empty($_POST["password"])) $passErr = "Password Required";
		else $password = test_input($_POST["password"]);
	}
	
  //if the user have entered both entries in the form, check if they exist in the database
	if(isset($username) && isset($password)&&
		!empty($username) && !empty($password)) {

		//check if entry exists in database
		if ($stmt = $mysqli->prepare("select * from member where username = ? and password = ?")) {
			$pass = md5($password);
			$stmt->bind_param("ss", $username, $pass);
			$stmt->execute();
			$stmt->bind_result($username, $password, $first_name, $last_name, $email, $zipcode);
			//if there is a match set session variables and send user to homepage
			if ($stmt->fetch()) {
				$_SESSION["username"] = $username;
				$_SESSION["password"] = $password;
				/*
				$_SESSION["first_name"] = $first_name;
				$_SESSION["last_name"] = $last_name;
				$_SESSION["email"] = $email;
				$_SESSION["zipcode"] = $zipcode;
				*/
				$_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"]; //store clients IP address to help prevent session hijack
				echo "Login successful\n";
				header("refresh: 1; index.php");
			}
			//if no match then tell them to try again
			else {
				sleep(1); //pause a bit to help prevent brute force attacks
				echo "Your username or password is incorrect, click <a href=\"login.php\">here</a> to try again.";
			}
			$stmt->close();
		}  
	}
  //if not then display login form
	else {
		echo "Enter your username and password below: <br />\n";
		echo '<form action="login.php" method="POST">';
		echo "\n";
		//if(empty($_POST['username'])) echo "Username field is empty, please enter a username </br>";
		//if(empty($_POST['password'])) echo "Password field is empty, please enter a password </br>";
		echo "</br>";
		echo 'Username: <input type="text" name="username" />';
		echo '<span class = "error"> * ';
		echo "$userErr";
		echo '</span> </br>';
		echo "\n";
		echo 'Password: <input type="password" name="password" />';
		echo '<span class = "error"> * ';
		echo "$passErr";
		echo '</span> </br>';
		echo "\n";
		echo '<input type="submit" value="Login" />';
		echo "\n";
		echo '</form>';
		echo "\n";
		echo '<br /><a href="index.php">Go back</a>';
	}	
}
$mysqli->close();
?>

</html>