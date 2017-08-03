<?php
require_once ('../mysqlConnect.php');
if(!isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['country']) || !isset($_POST['city'])){
  header("Location: customerRegistration.php");
}else{
  if(isset($_POST['address1']) && isset($_POST['district']) && isset($_POST['postalCode']) && isset($_POST['phoneNumber'])){
    $insertAddress = $conn->prepare("INSERT INTO ADDRESS (ADDRESS, ADDRESS2, DISTRICT, CITY_ID, POSTAL_CODE, PHONE) VALUES (?, ?, ?, ?, ?, ?)");
    $insertAddress->bind_param("isssiss", $address, $address2, $district, $cityId, $postalCode, $phone);
    $address = $_POST['address1'];
    $address2 = $_POST['address2'];
    $district = $_POST['district'];
    $cityId = $_POST['city'];
    $postalCode = $_POST['postalCode'];
    $phone = $_POST['phone'];
  }
  $emailRs = $conn->query("SELECT COUNT(*) AS CTR FROM CUSTOMER WHERE EMAIL = '".$_POST['email']."';");
  while($row = $emailRs->fetch_assoc()){
    $count = $row['CTR'];
  }
  if($count > 0){
    header("Location: customerRegistration.php?msg=emailError");
  }
  $addressRs = $conn->query("SELECT ADDRESS_ID, ADDRESS, ADDRESS2, DISTRICT, POSTAL_CODE FROM ADDRESS WHERE CITY_ID=".$_POST['city']." GROUP BY 2, 3, 4, 5 ORDER BY ADDRESS");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add New Customer</title>
  <?php include '..\/styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Customer Registration Part 2</h1>
        </div>
      </div><!--/.row-->
      <div class="row">
      	<div class="col-lg-12">
      		<div class="alert alert-info alert-dismissable">
      			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-info-circle"></i> <?php echo "New Customer <strong>".$_POST['firstName'].", ".$_POST['lastName']."</strong> in progress"; ?>
      		</div>
      	</div>
      </div><!-- /.row -->
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading"><strong>Existing Address</strong></div>
            <div class="panel-body">
              <table width="100%" class="table table-striped table-bordered table-hover" id="addrTable">
                <thead>
                  <tr>
                    <th>Address</th>
                    <th>Address 2</th>
                    <th>District</th>
                    <th>Postal Code</th>
                    <th>Select</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = $addressRs->fetch_assoc()){ ?>
                  <tr>
                    <td><?php echo $row['ADDRESS']; ?></td>
                    <td><?php echo $row['ADDRESS2']; ?></td>
                    <td><?php echo $row['DISTRICT']; ?></td>
                    <td><?php echo $row['POSTAL_CODE']; ?></td>
                    <td align="center"><button class="btn btn-info" onclick="dynamicInput('addressInfo.php', <?php echo $row['ADDRESS_ID'] ?>)"><strong>SELECT</strong></button></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div><!--/.panel-body-->
            <div class="panel-footer">
              --- End of Existing Addresses ---
            </div>
          </div><!--/.panel panel-defualt-->
        </div><!--/.col-lg-12-->
      </div><!--/.row-->
      <div class="row">
        <div class="col-lg-6">
          <div class="panel panel-info">
            <div class="panel-heading"><strong>Address Information</strong></div>
            <div class="panel-body">
              <div class="col-lg-12">
                <form action="addNewCustomer.php" method="post">
                  <input type="hidden" name="firstName" value="<?php echo $_POST['firstName']; ?>">
                  <input type="hidden" name="lastName" value="<?php echo $_POST['lastName']; ?>">
                  <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                  <input type="hidden" name="country" value="<?php echo $_POST['country']; ?>">
                  <input type="hidden" name="city" value="<?php echo $_POST['city'] ?>">
                  <div id="ajaxInput">
                  <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address1" placeholder="Address 1" class="form-control" required>
                    <p class="help-block">Required</p>
                  </div>
                  <div class="form-group">
                    <label>Address 2</label>
                    <input type="text" name="address2" placeholder="Address 2" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>District</label>
                    <input type="text" name="district" placeholder="District" class="form-control" required>
                    <p class="help-block">Required</p>
                  </div>
                  <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" name="postalCode" placeholder="Postal Code" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phoneNumber" placeholder="Phone Number" class="form-control" required>
                    <p class="help-block">Required</p>
                  </div>
                  <input type="submit" value="Submit" class="btn btn-success">
                  <input type="reset" value="Reset" class="btn btn-warning">
                  </div>
                </form>
              </div><!--/.col-lg-12-->
            </div><!--/.panel-body-->
          </div><!--/.panel panel-info-->
        </div><!--/.col-lg-6-->
      </div><!--/.row-->
    </div><!--/#page-wrapper-->
  </div><!--/#wrapper-->
</body>
<script>
$(document).ready(function(){
  $('#addrTable').DataTable({
    responsive: true
  });
});
function dynamicInput(ajaxPage, city){
  $.ajax({
    type: "POST",
    url: ajaxPage,
    data: "id=" + city,
    dataType: "html",
    success: function(result){
      $('#ajaxInput').html(result);
    }
  });
}
</script>
</html>
