<?php
$serverName = "localhost";
$userName = "root";
$password = "ASDkmdsdmkasdmal";
$dbName = "movierentaldb";
$conn = new mysqli($serverName, $userName, "", $dbName);

if($conn->connect_error){
  die("Connection Failed: " . $conn->connect_error);
}
?>
