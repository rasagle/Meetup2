<html>

<?php
include('include.php');
if($stmt = $mysqli->prepare("INSERT INTO belongs_to VALUES(?, ?, ?)")){
	$user = $_GET['username'];
	$groupID = $_GET['group_id'];
	$zero = 0;
	$stmt->bind_param("ssi", $groupID, $user, $zero);
	$stmt->execute();
	echo "You have successfully joined this group </br>";
	echo '<a href="index.php">Go back</a><br /><br />';
}
?>

</html>