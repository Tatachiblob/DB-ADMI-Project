<?php
require_once ('../mysqlConnect.php');
$countryRs = $conn->query("SELECT COUNTRY_ID, COUNTRY FROM COUNTRY ORDER BY COUNTRY;");
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
          <h1 class="page-header">Customer Registration</h1>
        </div>
      </div><!--/.row-->
      <?php if(isset($_GET['msg'])){ ?>
      <div class="row">
      	<div class="col-lg-12">
          <?php if($_GET['msg'] == 'emailError'){ ?>
      		<div class="alert alert-danger alert-dismissable">
      			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-info-circle"></i> <strong>Email Already Used.</strong>
      		</div>
          <?php }else if($_GET['msg'] == 'success'){ ?>
            <div class="alert alert-info alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-info-circle"></i> <strong>New Customer Account Registered.</strong>
            </div>
          <?php } ?>
      	</div>
      </div><!-- /.row -->
      <?php } ?>
      <div class="row">
        <div class="col-lg-6">
          <div class="panel panel-info">
            <div class="panel-heading"><strong>New Customer Form</strong></div>
            <div class="panel-body">
              <div class="col-lg-12">
                <form action="customerAddress.php" method="post">
                  <div class="form-group">
                    <label>First Name</label>
                    <input name="firstName" type="text" placeholder="First Name" class="form-control" required autofocus>
                    <p class="help-block">Required</p>
                  </div>
                  <div class="form-group">
                    <label>Last Name</label>
                    <input name="lastName" type="text" placeholder="Last Name" class="form-control" required>
                    <p class="help-block">Required</p>
                  </div>
                  <div class="form-group">
                    <label>Email Address</label>
                    <input name="email" type="email" placeholder="E-mail" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Customer Country</label>
                    <select name="country" class="form-control" required onchange="dynamicSelect('city.php', this.value)">
                      <option value="">Choose Country</option>
                      <?php while($row = $countryRs->fetch_assoc()){ ?>
                      <option value="<?php echo $row['COUNTRY_ID']; ?>"><?php echo $row['COUNTRY']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Customer City</label>
                    <div id="ajaxCity">
                    <select name="city" class="form-control">
                      <option value="">Choose City</option>
                    </select>
                    </div>
                  </div>
                  <input type="submit" value="Submit" class="btn btn-success">
                  <input type="reset" value="Reset" class="btn btn-warning">
                </form>
              </div>
            </div><!--/.panel-body-->
          </div><!--/.panel panel-info-->
        </div><!--/.col-lg-6-->
      </div><!--/.row-->
    </div><!--/#page-wrapper-->
  </div><!--/#wrapper-->
</body>
<script>
function dynamicSelect(ajaxPage, country){
  $.ajax({
    type: "GET",
    url: ajaxPage,
    data: "id=" + country,
    dataType: "html",
    success: function(result){
      $('#ajaxCity').html(result);
    }
  });
}
</script>
</html>
