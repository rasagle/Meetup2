<html>
<?php
include("include.php");
$event_info = $_GET["event_info"];

$r_explode = explode('|', $event_info);
$r_user = $r_explode[1];
$r_event = $r_explode[0];

if(isset($r_user) && isset($r_event)){
	if($stmt = $mysqli->prepare("INSERT INTO attend ( event_id,username, rsvp) VALUES ('$r_event', '$r_user', 1)")){
		$stmt->execute();
		echo"You have successfully RSVP for the event\n";
		echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
		header("refresh: 3; index.php");
	}
	else{
		echo "Failed\n";
		echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
		header("refresh: 3; index.php");
	}
}
else{
	echo "Cannot find\n";
	echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
	header("refresh: 3; index.php");
}
?>

</html>