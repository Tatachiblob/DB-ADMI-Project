<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
$abc = $conn->query("SELECT CUSTOMER_ID, FIRST_NAME, LAST_NAME FROM CUSTOMER WHERE STORE_ID = {$_SESSION['storeId']} ORDER BY 1;");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Return Movie</title>
  <?php include '..\/styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-heading">Return Movie</h1>
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
                  <select name="custId" class="form-control" required onchange="customerSelect('custAjax.php', this.value)">
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
    </div><!--/#page-wrapper-->
  </div><!--/#wrapper-->
</body>
</html>
