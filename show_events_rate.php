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
$rating = $evenID = "";
$ratingErr = $eventErr = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty($_POST["rating"])) $ratingErr = "Rating Required";
	else $rating = test_input($_POST["rating"]);
	
	if(empty($_POST["eventID"])) $eventErr = "Event Required";
	else $eventID = test_input($_POST["eventID"]);
	
}
if(isset($eventID) && isset($rating) && !empty($eventID) && !empty($rating)){
	if($stmt = $mysqli->prepare("SELECT username, event_id FROM events natural join attend
		WHERE end_time < CURDATE() AND username = ?")){
		$stmt->bind_param('s', $_GET["username"]);
		if(!$stmt->execute()) $stmt->err;
		$stmt->store_result();
		$stmt->bind_result($user, $event_id);
		if($stmt->num_rows > 0){
			$stmt->close();
			if($stmt = $mysqli->prepare("UPDATE attend SET rating = ? WHERE username = ? and event_id = ?")){
				$stmt->bind_param("iss", $rating, $_GET["username"], $eventID);
				if(!$stmt->execute()) $stmt->err;
				$stmt->close();
			}
		}
		else{
			if($stmt = $mysqli->prepare("INSERT INTO attend (event_id, username, rating) VALUES(?, ?, ?)")){
				$stmt->bind_param('isi', $eventID, $_GET['username'], $rating);
				if(!$stmt->execute()) $stmt->err;
				$stmt->close();
			}
		}
		echo "Rating successful, click <a href=\"index.php\">here</a> to return to homepage.";
	}
}
else{
	$username = $_GET["username"];
	if($stmt = $mysqli->prepare("SELECT title, event_id FROM events
		WHERE end_time < CURDATE()")){
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($title, $eventID);
		if($stmt->num_rows == 0){
			echo "There were no past events click <a href='index.php'>here</a> to go back. </br>";
			$stmt->close();
		}
		else{
			echo "<form action = 'show_events_rate.php?username=$username' method='POST'>";
			echo "Choose an event to rate<br/>";
			echo "<select name='eventID'>";
			while($stmt->fetch()){
				$eventID = htmlspecialchars($eventID);
				$eventName = htmlspecialchars($title);
				echo "<option value='$eventID'>$eventName</option>\n";	
			}
			echo '</select>';
			echo '<input type="radio" name="rating" value="1">1';
			echo '<input type="radio" name="rating" value="2">2';
			echo '<input type="radio" name="rating" value="3">3';
			echo '<input type="radio" name="rating" value="4">4';
			echo '<input type="radio" name="rating" value="5">5';
			echo '<span class = "error"> * ';
			echo "$ratingErr";
			echo '</span> </br>';
			echo '<input type = "submit" value = "Rate">';
			$stmt->close();
		}
	}
}
?>

</html>