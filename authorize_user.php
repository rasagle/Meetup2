<!DOCTYPE html>
<html>
<style>
.error {color:#FF0000;}
</style>
<?php
include("include.php");

function trim_input($data){
	$data = trim($data); // removes white space on both sides
	$data = stripslashes($data); // removes backslashes
	$data = htmlspecialchars($data); // turns special html chars into literals
	return $data;
}

// check if logged in and POST
if (isset($_SESSION["username"])) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["member_name"])) {
			if ($stmt = $mysqli->prepare("UPDATE belongs_to SET authorized=1 WHERE group_id=? AND username=?")){
				$member_name = trim_input($_POST["member_name"]);
				$stmt->bind_param("is", $_SESSION["group_id"], $member_name);
				$stmt->execute();
				$stmt->close();
				echo "The user has been authorized<br/>\n
				You will be redirected in 5 seconds or click <a href=\"index.php\">here</a>.\n";
				header("refresh: 5; index.php");
			}
		} else {
			echo "An error has occurred";
		}
	} else { // GET; display form
		if(isset($_GET["group_id"]) && 
			$stmt = $mysqli->prepare("SELECT username FROM belongs_to WHERE authorized = 0 AND group_id = ?")) {
			$group_id = trim_input($_GET["group_id"]);
			$stmt->bind_param("i", $group_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($member_name);
			if($stmt->num_rows > 0){
				echo "<form action=\"authorize_user.php\" method=\"POST\">Choose the user to authorize<br/>\n
				<select name=\"member_name\">";
				while($stmt->fetch()){
					echo "<option value=\"$member_name\">$member_name</option>\n";	
				}
				echo "</select>\n<input type=\"submit\" value=\"Authorize user\">";
				// store group id in session, do not store using form since user may change it manually
				$_SESSION["group_id"] = $group_id;
			} else{
				echo "There are no users to authorize<br/>\n
				You will be redirected in 5 seconds or click <a href=\"index.php\">here</a>.\n";
				header("refresh: 5; index.php");
			}
			$stmt->close();
		}
	}
} else {
	echo "You are not logged in<br/>\n
	You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
	header("refresh: 3; index.php");
}
$mysqli->close();
?>
</html>