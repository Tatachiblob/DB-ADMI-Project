<?php
session_start();
require_once ('../mysqlConnect.php');
$email = $_POST['email'];
$pass = $_POST['password'];
try{
  $conn->autocommit(FALSE);

  $userRs = $conn->query("SELECT STAFF_ID, FIRST_NAME, LAST_NAME, STORE_ID FROM STAFF WHERE USERNAME = '{$email}' AND PASSWORD = '{$pass}' AND ACTIVE = TRUE;");
  if(!$userRs){
    $userRs->free();
    throw new Exception($conn->error);
  }
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

  $conn->commit();
  $conn->autocommit(TRUE);
}catch(Exception $e){
  $conn->rollback();
  $conn->autocommit(TRUE);
}

if(isset($firstName) && isset($lastName) && isset($staffId) && isset($storeId)){
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
