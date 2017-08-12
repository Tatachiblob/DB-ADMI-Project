<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
$abc = $conn->query("SELECT C.CUSTOMER_ID, C.FIRST_NAME, C.LAST_NAME FROM CUSTOMER C JOIN RENTAL R ON C.CUSTOMER_ID = R.CUSTOMER_ID WHERE STORE_ID = {$_SESSION['storeId']} AND R.RETURN_DATE IS NULL GROUP BY 1 ORDER BY 1;");
$today = $conn->query("SELECT DATE(NOW()) AS NOW;");
while($row = $today->fetch_assoc()){
  $now = $row['NOW'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Return Movie</title>
  <?php include '../styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-heading">Return Movie(<?php echo $now; ?>)</h1>
        </div>
      </div><!--/.row-->
      <div class="row">
        <div class="col-lg-5">
          <div class="panel panel-info">
            <div class="panel-heading">Customer Selection</div>
            <div class="panel-body">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Customer</label>
                  <select name="custId" class="form-control" required onchange="changeReturn('rentalAjax.php', this.value)">
                    <option value="">Choose Customer</option>
                    <?php while($row = $abc->fetch_assoc()){
                    echo "<option value=\"{$row['CUSTOMER_ID']}\">#{$row['CUSTOMER_ID']} - {$row['FIRST_NAME']}, {$row['LAST_NAME']}</option>";
                    } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!--/.row-->
      <div id="ajaxReturn"></div><!--/#ajaxReturn-->
    </div><!--/#page-wrapper-->
  </div><!--/#wrapper-->
</body>
<script>
function changeReturn(ajaxPage, customer){
  $.ajax({
    type: "POST",
    url: ajaxPage,
    data: "id="+customer,
    dataType: "html",
    success: function(result){
      $('#ajaxReturn').html(result);
    }
  });
}
</script>
</html>
