<?php
require_once ('../mysqlConnect.php');
$addrInfo = $conn->query("SELECT ADDRESS_ID, ADDRESS, ADDRESS2, DISTRICT, POSTAL_CODE FROM ADDRESS WHERE ADDRESS_ID=".$_POST['id'].";");
while($row = $addrInfo->fetch_assoc()){
  $address1 = $row['ADDRESS'];
  $address2 = $row['ADDRESS2'];
  $district = $row['DISTRICT'];
  $postal = $row['POSTAL_CODE'];
}
?>
<div class="form-group">
  <label>Address</label>
  <input type="text" name="address1" placeholder="Address 1" class="form-control" value="<?php echo $address1 ?>" required autofocus>
  <p class="help-block">Required</p>
</div>
<div class="form-group">
  <label>Address 2</label>
  <input type="text" name="address2" placeholder="Address 2" class="form-control" value="<?php echo $address2 ?>">
</div>
<div class="form-group">
  <label>District</label>
  <input type="text" name="district" placeholder="District" class="form-control" value="<?php echo $district ?>" required>
  <p class="help-block">Required</p>
</div>
<div class="form-group">
  <label>Postal Code</label>
  <input type="text" name="postalCode" placeholder="Postal Code" class="form-control" value="<?php echo $postal ?>" required>
</div>
<div class="form-group">
  <label>Phone Number</label>
  <input type="text" name="phoneNumber" placeholder="Phone Number" class="form-control" required>
  <p class="help-block">Required</p>
</div>
<input type="submit" value="Submit" class="btn btn-success">
<input type="reset" value="Reset" class="btn btn-warning">
