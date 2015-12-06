<html>

<?php
include("include.php");
if($stmt = $mysqli->prepare("SELECT group_name, group_id 
	FROM groups
	WHERE group_id NOT IN
	(SELECT group_id FROM belongs_to join groups using (group_id)
	WHERE belongs_to.username = ?)")){
	$user = $_GET['username'];
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($gname, $groupID);
	if($stmt->num_rows >0){
		echo "Choose a group to join </br></br>";
		while($stmt->fetch()){
			echo "<a href = 'join_group.php?username=$user&group_id=$groupID'> $gname </a> </br>";
		}
	}
	else{
		echo "There are no groups that you are not in </br>";
	}
	$stmt->close();
	echo '<a href="index.php">Go back</a><br /><br />';
}
$mysqli->close();
?>

</html>