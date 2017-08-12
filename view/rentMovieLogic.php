<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
if(!isset($_POST['i']) && !isset($_POST['c']) && !isset($_POST['payment'])){
  header("Location: rentMoviePage.php");
}else{
  /*
  echo "<p>Customer: {$_POST['c']}</p>";
  echo "<p>Inventory: {$_POST['i']}</p>";
  echo "<p>Payment: {$_POST['payment']}</p>";
  */
  try{
    $conn->autocommit(FALSE);

    //check if customer already rented the same film TITLE
    $filmCheck = $conn->query("SELECT   F.FILM_ID
                               FROM     (SELECT * FROM RENTAL WHERE RETURN_DATE IS NULL) R
                               JOIN     (SELECT * FROM INVENTORY WHERE STORE_ID = {$_SESSION['storeId']}) I ON R.INVENTORY_ID = I.INVENTORY_ID
                               JOIN     FILM F ON I.FILM_ID = F.FILM_ID
                               WHERE    R.CUSTOMER_ID = {$_POST['c']};");
    $filmCheck2 = $conn->query("SELECT   F.FILM_ID
                               FROM     INVENTORY I
                               JOIN     FILM F ON I.FILM_ID = F.FILM_ID
                               WHERE    I.INVENTORY_ID = {$_POST['i']};");
    $filmCheckId1 = -1;
    $filmCheckId2 = -2;
    while($row = $filmCheck->fetch_assoc()){
      $filmCheckId1 = $row['FILM_ID'];
    }
    while($row = $filmCheck2->fetch_assoc()){
      $filmCheckId2 = $row['FILM_ID'];
    }
    if($filmCheckId1 == $filmCheckId2){
      throw new Exception("Customer has already rented this movie");
    }

    $insertRental = $conn->prepare("INSERT INTO RENTAL (INVENTORY_ID, CUSTOMER_ID , STAFF_ID) VALUES (?, ?, ?);");
    $insertPayment= $conn->prepare("INSERT INTO PAYMENT(CUSTOMER_ID, STAFF_ID, RENTAL_ID, AMOUNT, PAYMENT_DATE) VALUES (?, ?, ?, ?, NOW());");
    $insertRental->bind_param("iii", $inventoryId, $custId, $staffId);
    $insertPayment->bind_param("iiid", $custId, $staffId, $rentalId, $amount);
    $inventoryId = $_POST['i'];
    $custId = $_POST['c'];
    $amount = $_POST['payment'];
    $staffId = $_SESSION['staffId'];
    $insertRental->execute();
    $msg1 = $insertRental->error;
    if($msg1 != ""){
      throw new Exception($conn->error);
    }
    $rentalId = $conn->insert_id;
    echo "<p>{$rentalId}</p>";
    $insertPayment->execute();
    $msg2= $insertPayment->error;
    if($msg2 != ""){
      throw new Exception($conn->error);
    }

    $conn->commit();
    $conn->autocommit(TRUE);

    header("Location: rentMoviePage.php?success={$_POST['i']}");
  }catch(Exception $e){
    $conn->rollback();
    $conn->autocommit(TRUE);
    echo "<p>Error: $e</p>";
    echo "<a href=\"dashboard.php\">Back to Dashboard</a>";
  }
}
?>
