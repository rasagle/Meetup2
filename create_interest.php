<html>

<style>
.error {color:#FF0000;}
</style>
<?php
include("include.php");

function test_input($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
$interest_name = $interestErr = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty($_POST["name"])) $interestErr = "Interest Name Required";
	else $interest_name = test_input($_POST["name"]);
		
}

if(isset($interest_name) && !empty($interest_name)){
	if($stmt = $mysqli->prepare("SELECT interest_name FROM interest where interest_name = ?")){
		$stmt->bind_param("s", $interest_name);
		$stmt->execute();
		$stmt->bind_result($name);
		if($stmt->fetch()){
			//echo "That interest already exists <br/>";
			//echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.";
			//header("refresh: 3; index.php");
			$stmt->close();
			if($stmt = $mysqli->prepare("INSERT INTO interested_in (username, interest_name) VALUES(?, ?)")){
				$user = $_GET['username'];
				$stmt->bind_param('ss', $user, $interest_name);
				$stmt->execute();
				$stmt->close();
				echo "This interest has been added to your personal interests, it already exists in the global interest </br>";
				echo "Click <a href=\"index.php\">here</a> to return to homepage.";
			}
			else echo "error";
		}
		else{
			$stmt->close();
			if($stmt = $mysqli->prepare("INSERT into interest VALUES(?)")){
				$stmt->bind_param("s", $interest_name);
				$stmt->execute();
				$stmt->close();
				if($stmt = $mysqli->prepare("INSERT INTO interested_in (username, interest_name) VALUES(?, ?)")){
					$user = $_GET["username"];
					$stmt->bind_param('ss', $user, $interest_name);
					if(!$stmt->execute()) echo "$stmt->error";
					$stmt->close();
					echo "This interest has been added to your personal interests and the global interests </br>";
					echo "Click <a href=\"index.php\">here</a> to return to homepage.";
				}
				else echo "error";
			}
		}
	}
	else{
		echo "Something went wrong";
	}
}
else{
	$username = $_GET["username"];
	echo "Enter interest name below: <br /><br />\n";
    echo "<form action='create_interest.php?username=$username' method='POST'>";
    echo "\n";	
	echo 'Interest Name: <input type="text" name="name" />';
	echo '<span class = "error"> * ';
	echo "$interestErr";
	echo '</span> </br>';
	echo '<input type="submit" value="Create" />';
    echo "\n";
	echo '</form>';
	echo "\n";
	echo '<br /><a href="index.php">Go back</a>';
}
$mysqli->close();
?>
</html>