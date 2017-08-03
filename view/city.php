<?php
require_once ('../mysqlConnect.php');
$cityRs = $conn->query("SELECT CITY_ID, CITY FROM CITY WHERE COUNTRY_ID = ".$_GET['id']." ORDER BY CITY;");
?>
<select name="city" class="form-control" required>
  <option value="">Choose City</option>
  <?php while($row = $cityRs->fetch_assoc()){ ?>
  <option value="<?php echo $row['CITY_ID']; ?>"><?php echo $row['CITY']; ?></option>
  <?php } ?>
</select>
