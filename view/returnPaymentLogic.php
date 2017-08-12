<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
if(isset($_POST['r']) && isset($_POST['c']) && isset($_POST['payment'])){

  try{
    $conn->autocommit(FALSE);

    if($_POST['payment'] != 0){
      $insertPayment = $conn->prepare("INSERT INTO PAYMENT (CUSTOMER_ID, STAFF_ID, RENTAL_ID, AMOUNT, PAYMENT_DATE) VALUES (?, ?, ?, ?, NOW());");
      $insertPayment->bind_param("iiid", $custId, $staffId, $rentalId, $amount);
      $custId = $_POST['c'];
      $staffId = $_SESSION['staffId'];
      $rentalId = $_POST['r'];
      $amount = $_POST['payment'];
      $insertPayment->execute();
      echo "<p>INSIDE PAYMENT BLOCK: {}</p>";
      if($insertPayment->error != ""){
        throw new Exception($conn->error);
      }
    }
    $updateRental = $conn->prepare("UPDATE RENTAL SET RETURN_DATE = NOW() WHERE RENTAL_ID = ?;");
    $updateRental->bind_param("i", $rentalId);
    $rentalId = $_POST['r'];
    $updateRental->execute();
    if($updateRental->error != ""){
      throw new Exception($conn->error);
    }
    echo "<p>RentalID: {$_POST['r']}</p><p>CustomerID: {$_POST['c']}</p><p>Payment: {$_POST['payment']}</p>";
    $conn->commit();
    $conn->autocommit(TRUE);
    header("Location: returnMoviePage.php?success={$rentalId}");
  }catch(Exception $e){
    $conn->rollback();
    $conn->autocommit(TRUE);
    echo "<p>Error: {$e}</p>";
    echo "<a href=\"dashboard.php\">Back to Dashboard</a>";
  }
}
?>
