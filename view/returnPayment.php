<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
if(isset($_GET['i']) && isset($_GET['c'])){
  $custRs = $conn->query("SELECT FIRST_NAME, LAST_NAME FROM CUSTOMER WHERE CUSTOMER_ID = {$_GET['c']};");
  $filmRs = $conn->query("SELECT F.TITLE, F.RENTAL_RATE FROM FILM F JOIN INVENTORY I ON F.FILM_ID = I.FILM_ID WHERE I.INVENTORY_ID = {$_GET['f']};");
  while($row = $custRs->fetch_assoc()){
    $customer = $row['FIRST_NAME'].", ".$row['LAST_NAME'];
  }
  while($row = $filmRs->fetch_assoc()){
    $film = $row['TITLE'];
  }
  $returnSql = "SELECT      CASE
                              WHEN DATEDIFF(NOW(), ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION)) < 0 THEN 0
                              ELSE ROUND(CEILING(DATEDIFF(NOW(), ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION)) / F.RENTAL_DURATION) * F.RENTAL_RATE, 2)
                            END AS PENALTIES
                FROM        FILM F
                JOIN        INVENTORY I ON F.FILM_ID = I.FILM_ID
                JOIN        RENTAL R ON I.INVENTORY_ID = R.INVENTORY_ID
                WHERE       R.RETURN_DATE IS NULL AND R.RENTAL_ID = {$_GET['i']};";
  $rs = $conn->query($returnSql);
  while($row = $rs->fetch_assoc()){
    $penalty = $row['PENALTIES'];
  }
}else{
  header("Location: returnMoviePage.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Payment</title>
  <?php include '../styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-heading">Payment</h1>
        </div>
      </div><!--/.row-->
      <div class="row">
        <div class="col-lg-5">
          <div class="panel panel-info">
            <div class="panel-heading">Payment Details</div>
            <div class="panel-body">
              <div class="col-lg-12">
                <form action="returnPaymentLogic.php" method="post" id="paymentForm">
                  <input type="hidden" name="r" value="<?php echo $_GET['i']; ?>">
                  <input type="hidden" name="c" value="<?php echo $_GET['c']; ?>">
                  <div class="form-group">
                    <label>Customer: <?php echo $customer; ?></label>
                  </div>
                  <div class="form-group">
                    <label>Movie: <?php echo $film; ?></label>
                  </div>
                  <div class="form-group">
                    <label>Amount to Pay: $<?php echo $penalty; ?></label>
                    <input type="number" min="0" step="0.01" name="payment" placeholder="Payment" class="form-control" id="payment" required <?php if($penalty == 0) echo "readonly"; ?>>
                  </div>
                  <input type="submit" id="hit" hidden>
                </form>
              <button class="btn btn-success" onclick="checkPayment()">Pay Rental</button>
              </div>
            </div>
          </div>
        </div>
      </div><!--/.row-->
    </div><!--/#page-wrapper-->
  </div><!--/#wrapper-->
</body>
<script>
function checkPayment(){
  var penalty = <?php echo $penalty; ?>;
  var payment = document.getElementById('payment').value;
  if(penalty == 0){
    document.getElementById('payment').value = 0;
  }
  if(penalty == payment || penalty == 0){
    document.getElementById('hit').click();
  }else{
    alert("Payment Amount is not Equal to the Rental Rate!");
  }
}
</script>
</html>
