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
  #echo "<p>ADDING</p>";
  #echo "<p>{$_POST['actor']}</p>";
  $insert = $conn->prepare("INSERT INTO FILM_ACTOR (ACTOR_ID, FILM_ID) VALUES (?, ?);");
  $insert->bind_param("ii", $actor, $movie);
  $actor = $_POST['actor'];
  $movie = $_POST['movieId'];
  $insert->execute();
  $msg = $insert->error;
  if($msg == ""){
    #echo "<p>Success Adding a Actor to a Movie.</p>";
    header("Location: addActor.php?id={$movie}");
  }else{
    echo "<p>Error: {$msg}</p>";
    echo "<a href="dashboard.php">Back to Dashboard</a>";
    #header("Location: addActor.php?id={$movie}&error={$msg}");
  }
  #echo "<p>Movie: {$movie}</p><p>Actor: {$actor}</p>";
}
if($_GET['action'] == "d"){
  #echo "<p>D</p>";
  $delete = $conn->prepare("DELETE FROM FILM_ACTOR WHERE ACTOR_ID = ? AND FILM_ID = ?;");
  $delete->bind_param("ii", $actor, $film);
  $film = $_GET['m'];
  $actor = $_GET['a'];
  $delete->execute();
  $msg = $delete->error;
  if($msg == ""){
    #echo "<p>Success Deleting a Actor from a Movie.</p>";
    header("Location: addActor.php?id={$film}");
  }else{
    echo "<p>Error: {$msg}</p>";
    echo "<a href=\"dashboard.php\">Back to Dashboard</a>";
    #header("Location: addActor.php?id={$movie}&error={$msg}");
  }
  #echo "<p>{$film}</p><p>{$actor}</p>";
}
?>
