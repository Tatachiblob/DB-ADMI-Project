<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
if(isset($POST['title']) || isset($_POST['langId']) || isset($_POST['rentalDuration']) || isset($_POST['rentalRate']) || isset($_POST['replacementCost'])){
  try{
    $conn->autocommit(TRUE);

    $updateMovie = $conn->prepare("UPDATE FILM SET TITLE=?, DESCRIPTION=?, RELEASE_YEAR=?, LANGUAGE_ID=?, ORIGINAL_LANGUAGE_ID=?, RENTAL_DURATION=?, RENTAL_RATE=?, LENGTH=?, REPLACEMENT_COST=?, RATING=?, SPECIAL_FEATURES=? WHERE FILM_ID=?;");
    $updateMovie->bind_param("ssiiiididssi", $movieTitle, $movieDesc, $releaseYear, $langId, $origLangId, $rentalDuration, $rentalRate, $movieLength, $replaceCost, $movieRate, $sp, $filmId);
    $filmId = $_POST['filmId'];
    $movieTitle = $_POST['title'];
    if($_POST['desc'] != ""){
      $movieDesc = $_POST['desc'];
    }else{
      $movieDesc = NULL;
    }

    $releaseYear = $_POST['releaseYear'];
    $langId = $_POST['langId'];

    if($_POST['origLangId']){
      $origLangId = $_POST['origLangId'];
    }else{
      $orgiLangId = NULL;
    }

    if($_POST['rentalDuration'] != ""){
      $rentalDuration = $_POST['rentalDuration'];
    }else{
      $rentalDuration = 3;
    }

    if($_POST['rentalRate'] != ""){
      $rentalRate = $_POST['rentalRate'];
    }else{
      $rentalRate = 4.99;
    }

    if($_POST['movieLength'] != ""){
      $movieLength = $_POST['movieLength'];
    }else{
      $movieLength = NULL;
    }

    if($_POST['replacementCost'] != ""){
      $replaceCost = $_POST['replacementCost'];
    }else{
      $replaceCost = 19.99;
    }

    if($_POST['movieRating'] != ""){
      $movieRate = $_POST['movieRating'];
    }else{
      $movieRate = "G";
    }

    if($_POST['specialFeature'] != ""){
      $specialFeature = $_POST['specialFeature'];
    }else{
      $specialFeature = NULL;
    }

    $sp = "";
    /*
    echo "
      <p>Movie Title: {$movieTitle}</p>
      <p>Movie Description: {$movieDesc}</p>
      <p>Released Year: {$releaseYear}</p>
      <p>Language: {$langId}</p>
      <p>Original Language: {$origLangId}</p>
      <p>Rental Duration: {$rentalDuration}</p>
      <p>Rental Rate: {$rentalRate}</p>
      <p>Replacement Cost: {$replaceCost}</p>
      <p>Movie Rating: {$movieRate}</p>
    ";*/

    for($val = 0; $val < count($specialFeature); $val++){

      if($val == count($specialFeature) - 1){
        $sp = $sp.$specialFeature[$val];
      }else{
        $sp = $sp.$specialFeature[$val].",";
      }
    }
    #echo "<p>Special Features: {$sp}</p>";
    $updateMovie->execute();
    $msg = $updateMovie->error;
    if(!$updateMovie){
      $updateMovie->free();
      throw new Exception($conn->error);
    }

    $conn->commit();
    $conn->autocommit(TRUE);
  }catch(Exception $e){
    $conn->rollback();
    $conn->autocommit(TRUE);
  }
  if($msg == ""){
    header("Location: editFilm.php?msg=success");
  }else{
    echo "<p>Error: {$msg}</p>";
    echo "<a href=\"dashboard.php\">Back to Dashboard</a>";
  }
}else{
  header("Location: dashboard.php");
}
?>
