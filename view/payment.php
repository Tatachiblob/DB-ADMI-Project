<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
if(isset($_GET['c']) && isset($_GET['i'])){
  $custRs = $conn->query("SELECT FIRST_NAME, LAST_NAME FROM CUSTOMER WHERE CUSTOMER_ID = {$_GET['c']};");
  $filmRs = $conn->query("SELECT F.TITLE, F.RENTAL_RATE FROM FILM F JOIN INVENTORY I ON F.FILM_ID = I.FILM_ID WHERE I.INVENTORY_ID = {$_GET['i']};");
  while($row = $custRs->fetch_assoc()){
    $customer = $row['FIRST_NAME'].", ".$row['LAST_NAME'];
  }
  while($row = $filmRs->fetch_assoc()){
    $film = $row['TITLE'];
    $rate = $row['RENTAL_RATE'];
  }
}else{
  header("Location: rentMoviePage.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Payment</title>
  <?php include '..\/styleInclude.html'; ?>
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
                <form action="rentMovieLogic.php" method="post" id="paymentForm">
                  <input type="hidden" name="i" value="<?php echo $_GET['i']; ?>">
                  <input type="hidden" name="c" value="<?php echo $_GET['c']; ?>">
                  <input type="hidden" name="p" value="<?php echo $rate; ?>" id="rate">
                  <div class="form-group">
                    <label>Customer: <?php echo $customer; ?></label>
                  </div>
                  <div class="form-group">
                    <label>Movie: <?php echo $film; ?></label>
                  </div>
                  <div class="form-group">
                    <label>Amount to Pay: $<?php echo $rate; ?></label>
                    <input type="number" min="0" step="0.01" name="payment" placeholder="Payment" class="form-control" id="payment" required>
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
  var rate = document.getElementById('rate').value;
  var payment = document.getElementById('payment').value;
  if(rate == payment){
    document.getElementById('hit').click();
  }else{
    alert("Payment Amount is not Equal to the Rental Rate!");
  }
}
</script>
</html>
