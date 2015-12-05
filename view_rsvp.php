<html>
<?php

include("include.php");
//Shows all the events that the user has RSVP for
if($stmt = $mysqli->prepare("SELECT event_id, title, description, TIME_FORMAT(start_time, '%h:%i %p'), TIME_FORMAT(end_time,'%h:%i %p'), events.zip, lname, DATE_FORMAT(start_time, '%m-%d-%y')
	FROM (member natural join attend) join (events natural join location) using (event_id)
	WHERE username = ? and rsvp = 1")){
	$stmt->bind_param("s", $_GET["username"]);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($eId, $eName, $eDesc, $eStart, $eEnd, $zipcode, $location_name, $date);
	if($stmt->num_rows > 0){
		//$stmt->bind_param("s", $_GET["username"]);
		//$stmt->execute();
		//$stmt->bind_result($eId, $eName, $eDesc, $eStart, $eEnd, $eDate);
		echo "<table border = 1>\n";
		echo"<tr><th>Event ID </th> <th>Event Name</th>
			<th>Description</th><th>Start time</th><th>End time</th><th>Date </th><th>Zipcode</th><th>Location Name</th></tr>\n";
		while($stmt->fetch()){
			echo"<tr><td>$eId</td><td>$eName</td><td>$eDesc</td><td>$eStart</td>
			<td>$eEnd</td><td>$date</td><td>$zipcode</td><td>$location_name</td></tr>";
		}
		echo "</table>";
	}
	else{
		echo "You did not RSVP for any events";
	}
	
	$stmt->close();
}
else{
	echo "Some error has occured";
}

?>

</html>