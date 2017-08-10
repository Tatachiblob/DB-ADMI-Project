<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
  header("Location: login.php");
}
if(!isset($_GET['action'])){
  header("Location: dashboard.php");
}
if($_GET['action'] == "add"){
  #echo "<p>Category Adding</p>";
  #echo "<p>{$_POST['category']}</p>";
  $insert = $conn->prepare("INSERT INTO FILM_CATEGORY(CATEGORY_ID, FILM_ID) VALUES (?, ?);");
  $insert->bind_param("ii", $category, $film);
  $category = $_POST['category'];
  $film = $_POST['movieId'];
  $insert->execute();
  $msg = $insert->error;
  if($msg == ""){
    header("Location: addActor.php?id={$film}");
  }else{
    echo "<p>{$msg}</p>";
    echo "<a href=\"dashboard.php\">Back to Dashboard</a>";
  }
}
if($_GET['action'] == "d"){
  $delete = $conn->prepare("DELETE FROM FILM_CATEGORY WHERE CATEGORY_ID = ? AND FILM_ID = ?");
  $delete->bind_param("ii", $category, $film);
  $film = $_GET['m'];
  $category = $_GET['c'];
  $delete->execute();
  $msg = $delete->error;
  if($msg == ""){
    header("Location: addActor.php?id={$film}");
  }else{
    echo "<p>Error: {$msg}</p>";
    echo "<a href=\"dashboard.php\">Back to Dashboard</a>";
  }
}
?>
