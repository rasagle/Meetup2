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

$event_name = $desc = $start = $end = $street = $long = $lat = $city = $zip = $loc_name = "";
$eventErr = $descErr = $startErr = $endErr = $streetErr = $longErr = $latErr = $cityErr = $zipErr = $locErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty($_POST["name"])) $eventErr = "Event Name Required";
	else $event_name = test_input($_POST["name"]);
	
	if(empty($_POST['description'])) $descErr = "Description Required";
	else $desc = test_input($_POST["description"]);
	
	if(empty($_POST["start_time"])) $startErr = "Start Time Required";
	else $start = test_input($_POST["start_time"]);
	
	if(empty($_POST["end_time"])) $endErr = "End Time Required";
	else $end = test_input($_POST["end_time"]);
	
	if(empty($_POST["street"])) $streetErr = "Street Required";
	else $street = test_input($_POST["street"]);
	
	if(empty($_POST["city"])) $cityErr = "City Required";
	else $city = test_input($_POST["city"]);
	
	if(empty($_POST["longitude"])) $longErr = "Longitude Required";
	else $long = test_input($_POST["longitude"]);
	
	if(empty($_POST["latitude"])) $latErr = "Latitude Required";
	else $lat = test_input($_POST["latitude"]);
	
	if(empty($_POST["zipcode"])) $zipErr = "Zipcode Required";
	else $zip = test_input($_POST["zipcode"]);
	
	if(empty($_POST["location_name"])) $locErr = "Location Name Required";
	else $loc_name = test_input($_POST["location_name"]);
		
}

if(isset($event_name) && isset($desc) && isset($start) &&
	isset($end) && isset($street) && isset($long) && isset($lat) &&
	isset($city) && isset($zip) && isset($loc_name) &&
	!empty($event_name) && !empty($desc) && !empty($start) &&
	!empty($end) && !empty($street) && !empty($long) && !empty($lat) &&
	!empty($city) && !empty($zip) && !empty($loc_name)){
		
	if($stmt = $mysqli->prepare("INSERT INTO location (lname, zip, street, city, description, latitude, longitude)
			VALUES(?, ?, ?, ?, ?, ?, ?)")){
			$stmt->bind_param("sisssss", $loc_name, $zip, $street, $city,
							$desc, $lat, $long);
			$stmt->execute();
			$stmt->close();
		}
	
	if($stmt = $mysqli->prepare("INSERT INTO events (title, description, start_time, end_time, lname, zip, group_id)
		VALUES(?, ?, ?, ?, ?, ?, ?)")){
		$stmt->bind_param("sssssii", $event_name, $desc, $start, $end,
						$loc_name, $zip, $_GET["group_id"]);
		$stmt->execute();
		$stmt->close();
		
		echo "Successfully created an event, to go back, click <a href=\"index.php\">here</a> to return to homepage.";
	}
}
else{
	$group_id = $_GET["group_id"];
	echo "Enter your information below: <br /><br />\n";
    echo "<form action='create_event.php?group_id=$group_id' method='POST'>";
    echo "\n";	
    echo 'Event Name: <input type="text" name="name" />';
	echo '<span class = "error"> * ';
	echo "$eventErr";
	echo '</span> </br>';
    echo "\n";
	echo 'Short description of Event: <input type="text" name="description" />';
	echo '<span class = "error"> * ';
	echo "$descErr";
	echo '</span> </br>';
    echo "\n";
	echo 'Starting time (Enter date and time in 24hr format) </br>';
	echo 'yyyy-mm-dd hh:mm: <input type="text" name = "start_time" />';
	echo '<span class = "error"> * ';
	echo "$startErr";
	echo '</span> </br>';
	echo "\n";
	echo 'End time (Enter date and time in 24hr format) </br>';
	echo 'yyyy-mm-dd hh:mm: <input type="text" name = "end_time" />';
	echo '<span class = "error"> * ';
	echo "$endErr";
	echo '</span> </br>';
	echo "\n";
	echo 'Enter location name: <input type="text" name = "location_name" />';
	echo '<span class = "error"> * ';
	echo "$locErr";
	echo '</span> </br>';
	echo "\n";
	echo 'Enter street: <input type="text" name = "street" /> ';
	echo '<span class = "error"> * ';
	echo "$streetErr";
	echo '</span> </br>';
	echo "\n";
	echo 'Enter city: <input type="text" name = "city" />';
	echo '<span class = "error"> * ';
	echo "$cityErr";
	echo '</span> </br>';
	echo "\n";
	echo 'Enter zipcode: <input type="text" name = "zipcode" />';
	echo '<span class = "error"> * ';
	echo "$zipErr";
	echo '</span> </br>';
	echo "\n";
	echo 'Enter longitude: <input type="text" name = "longitude" />';
	echo '<span class = "error"> * ';
	echo "$longErr";
	echo '</span> </br>';
	echo "\n";
	echo 'Enter latitude: <input type="text" name = "latitude" />';
	echo '<span class = "error"> * ';
	echo "$latErr";
	echo '</span> </br>';
	echo "\n";
	echo '<input type="submit" value="Create" />';
    echo "\n";
	echo '</form>';
	echo "\n";
	echo '<br /><a href="index.php">Go back</a>';
}
$mysqli->close();
?>
</html>