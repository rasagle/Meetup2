<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Logout</title>

<?php
session_start();
session_destroy();
echo "You have signed out";
header("refresh: 1; index.php");
?>

</html>