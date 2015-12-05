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
$group_name = $group_desc = $int_name = "";
$groupErr = $descErr = $intErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty($_POST["gname"])) $groupErr = "Group Name Required";
	else $group_name = test_input($_POST["gname"]);
		
	if(empty([$_POST['description']])) $descErr = "Description Required";
	else $group_desc = test_input($_POST["description"]);
	
	if(empty($_POST["interest_name"])) $intErr = "Interest Name Required";
	else $int_name = test_input($_POST["interest_name"]);
}

if(isset($group_name) && isset($group_desc) && isset($int_name) &&
	!empty($group_name) && !empty($group_desc) && !empty($int_name)){
	//Checks to see if interest is already listed, if not, adds to database
	if($stmt = $mysqli->prepare("SELECT interest_name FROM interest WHERE interest_name = ?")){
		$stmt->bind_param("s", $int_name);
		$stmt->execute();
		$stmt->bind_result($i_name);
		if(!$stmt->fetch()){
			$stmt->close();
			$i_name = $int_name;
			if($stmt = $mysqli->prepare("INSERT into interest values('$i_name')")){
				$stmt->execute();
				$stmt->close();
			}
		}else{
			$stmt->close();
		}
	}
	
	if($stmt = $mysqli->prepare("INSERT INTO groups (group_name, description, username) VALUES(?,?, ?)")){
		!$stmt->bind_param("sss", $group_name, $group_desc, $_GET["username"]);
		//trigger_error($mysqli->error, E_USER_ERROR);
		$stmt->execute();
		$stmt->close();
		if($stmt = $mysqli->prepare("INSERT INTO group_interest VALUES (?, LAST_INSERT_ID())")){
			$stmt->bind_param("s", $int_name);
			$stmt->execute();
			$stmt->close();

		}
		if($stmt = $mysqli->prepare("INSERT INTO belongs_to VALUES(LAST_INSERT_ID(), ?, ?)")){
			$one = 1;
			$stmt->bind_param("si", $_GET['username'], $one);
			$stmt->execute();
			$stmt->close();
		}
	}else{
		echo"Did not create group <br/>";
	}
	
	$mysqli->close();
	echo "You have successfully created a group, you will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.";
	header("refresh: 3; index.php");
}

else{
	$username = $_GET['username'];
	echo "Enter your information below: <br /><br />\n";
	echo "<form action='create_group.php?username=$username' method='POST'>";
	echo "\n";	
	echo 'Group Name: <input type="text" name="gname" />';
	echo '<span class = "error"> * ';
	echo "$groupErr";
	echo '</span> </br>';
	echo 'Group Description: <input type="text" name = "description" /> ';
	echo '<span class = "error"> * ';
	echo "$descErr";
	echo '</span> </br>';
	echo "Interest name: <input type = 'text' name = 'interest_name'/>";
	echo '<span class = "error"> * ';
	echo "$intErr";
	echo '</span> </br>';
	echo "<input type = 'hidden' name = 'username' value = $username/>";
	echo '<input type="submit" value="Create Group" />';
	echo "\n";
	echo '</form>';
	echo "\n";
	echo '<br /><a href="index.php">Go back</a>';
}

?>

</html>