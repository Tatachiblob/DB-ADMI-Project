<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
if(!isset($_GET['action'])){
  header("Location: dashboard.php");
}else{
  if($_GET['action'] == "modify"){
    if(!isset($_POST['movieId'])){
      header("Location: dashboard.php");
    }else{
      header("Location: addActor.php?id={$_POST['movieId']}");
    }
  }
}
?>
