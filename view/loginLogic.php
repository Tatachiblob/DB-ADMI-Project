<?php
session_start();
require_once ('../mysqlConnect.php');
$email = $_POST['email'];
$pass = $_POST['password'];
$userRs = $conn->query("SELECT STAFF_ID, FIRST_NAME, LAST_NAME, STORE_ID FROM STAFF WHERE USERNAME = '{$email}' AND PASSWORD = '{$pass}' AND ACTIVE = TRUE;");
$staffId = null;
$firstName = null;
$lastName = null;
$storeId = null;
while($row = $userRs->fetch_assoc()){
  $staffId = $row['STAFF_ID'];
  $firstName = $row['FIRST_NAME'];
  $lastName = $row['LAST_NAME'];
  $storeId = $row['STORE_ID'];
}
if(isset($firstName)){
  $_SESSION['isLogin'] = True;
  $_SESSION['staffId'] = $staffId;
  $_SESSION['firstName'] = $firstName;
  $_SESSION['lastName'] = $lastName;
  $_SESSION['storeId'] = $storeId;
  header("Location: dashboard.php");
}else{
  header("Location: login.php?error=loginError");
}
?>
