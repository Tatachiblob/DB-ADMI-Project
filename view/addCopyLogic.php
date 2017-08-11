<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
if(!isset($_GET['m'])){
  header("Location: addInventory.php");
}else{
  $insert = $conn->prepare("INSERT INTO INVENTORY(FILM_ID, STORE_ID) VALUES (?, ?);");
  $insert->bind_param("ii", $movieId, $storeId);
  $movieId = $_GET['m'];
  $storeId = $_SESSION['storeId'];
  $insert->execute();
  $msg = $insert->error;
  if($msg == ""){
    header("Location: addInventory.php?success={$_GET['n']}");
  }else{
    echo "<p>Error: {$msg}</p>";
    echo "<a href=\"dashboard.php\">Back to Dashboard</a>";
  }
}
?>
