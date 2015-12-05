<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Register</title>
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
	echo "You are already logged in. ";
	echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.";
	header("refresh: 3; index.php");
}
else {
	$usernamePRIME = $password = $first_name = $last_name = $email = $zipcode = "";
	$userErr = $passErr = $firstErr = $lastErr = $emailErr = $zipErr = "";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(empty($_POST["username"])) $userErr = "Username Required";
		else $usernamePRIME = test_input($_POST["username"]);
		
		if(empty($_POST["password"])) $passErr = "Password Required";
		else $password = test_input($_POST["password"]);
		
		if(empty($_POST['first_name'])) $firstErr = "First Name Required";
		else $first_name = test_input($_POST["first_name"]);
		
		if(empty($_POST['last_name'])) $lastErr = "Last Name Required";
		else $last_name = test_input($_POST["last_name"]);
		
		if(empty($_POST['email'])) $emailErr = "Email Required";
		else {
			$email = test_input($_POST["email"]);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid email format"; 
			}
		}
		if(empty($_POST['zipcode'])) $zipErr = "Zipcode Required";
		else $zipcode = test_input($_POST["zipcode"]);
	}
	
	//if the user have entered _all_ entries in the form, insert into database
	if(isset($usernamePRIME) && isset($password) && isset($first_name) && 
		isset($last_name) && isset($email) && isset($zipcode) &&
		!empty($usernamePRIME) && !empty($password) && !empty($first_name)&&
		!empty($last_name) && !empty($email) && !empty($zipcode) && $emailErr == "") {

		//check if username already exists in database
		if ($stmt = $mysqli->prepare("select username from member where username = ?")) {
			$stmt->bind_param("s", $usernamePRIME);
			$stmt->execute();
			$stmt->bind_result($username);
			if ($stmt->fetch()) {
				echo "That username already exists. ";
				echo "You will be redirected in 3 seconds or click <a href=\"register.php\">here</a>.";
				header("refresh: 3; register.php");
				$stmt->close();
			}
			//if not then insert the entry into database, note that user_id is set by auto_increment
			else {
				$stmt->close();
				if ($stmt = $mysqli->prepare("insert into member (username,password,first_name,last_name, email,zipcode) values (?,?,?,?,?,?)")) {
					$pass = md5($password);
					$stmt->bind_param("sssssi", $usernamePRIME, $pass, $first_name,
					$last_name, $email, $zipcode);
					$stmt->execute();
					$stmt->close();
					echo "Registration complete, click <a href=\"index.php\">here</a> to return to homepage."; 
				}		  
			}	 
		}
	}
	  //if not then display registration form
	else {
		echo "Enter your information below: <br />\n";
		echo '<form action="register.php" method="POST">';
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
		echo 'First Name: <input type="text" name = "first_name" />';
		echo '<span class = "error"> * ';
		echo "$firstErr";
		echo '</span> </br>';
		echo "\n";
		echo 'Last Name: <input type="text" name = "last_name" />';
		echo '<span class = "error"> * ';
		echo "$lastErr";
		echo '</span> </br>';
		echo "\n";
		echo 'Email: <input type="text" name = "email" />';
		echo '<span class = "error"> * ';
		echo "$emailErr";
		echo '</span> </br>';
		echo "\n";
		echo 'Zipcode: <input type="text" name = "zipcode" />';
		echo '<span class = "error"> * ';
		echo "$zipErr";
		echo '</span> </br>';
		echo '<input type="submit" value="Register" />';
		echo "\n";
		echo '</form>';
		echo "\n";
		echo '<br /><a href="index.php">Go back</a>';

	}
}
$mysqli->close();


?>


</html>