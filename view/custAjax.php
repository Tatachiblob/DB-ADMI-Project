<?php
session_start();
require_once ('../mysqlConnect.php');
$custId = $_POST['id'];
$store = $_SESSION['storeId'];
$countSql = "SELECT		C.CUSTOMER_ID, C.STORE_ID, C.FIRST_NAME, C.LAST_NAME, COUNT(*) AS COUNT
             FROM     RENTAL R
             JOIN     CUSTOMER C ON R.CUSTOMER_ID = C.CUSTOMER_ID
             WHERE    R.RETURN_DATE IS NULL AND R.CUSTOMER_ID = {$custId}
             GROUP BY C.CUSTOMER_ID;";
$inventorySql = "SELECT		I.INVENTORY_ID, I.STATUS, F.TITLE, F.RELEASE_YEAR, F.RENTAL_RATE
                 FROM     FILM F
                 JOIN     (SELECT * FROM INVENTORY WHERE STORE_ID = {$_SESSION['storeId']} AND STATUS = 2) I ON F.FILM_ID = I.FILM_ID
                 ORDER BY 3";
$movieCountRs = $conn->query($countSql);
$inventoryRs = $conn->query($inventorySql);
$movieCount = -1;
while($row = $movieCountRs->fetch_assoc()){
  $movieCount = $row['COUNT'];
  $custName = $row['FIRST_NAME'].", ".$row['LAST_NAME'];
}
?>
<div class="row">
  <div class="col-lg-6">
    <?php if($movieCount == 3){ ?>
    <div class="alert alert-danger"><?php echo "{$custName} has already 3 movies rented"; ?></div>
    <?php }else{ ?>
    <div class="panel panel-info">
      <div class="panel-heading">Available Movies for Rent</div>
      <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover" id="movieTable">
          <thead>
            <tr>
              <th>Invenotry</th>
              <th>Film Title</th>
              <th>Release Year</th>
              <th>Rental Rate</th>
              <th>Rent Movie</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $inventoryRs->fetch_assoc()){ ?>
            <tr>
              <td><?php echo $row['INVENTORY_ID']; ?></td>
              <td><?php echo $row['TITLE']; ?></td>
              <td><?php echo $row['RELEASE_YEAR']; ?></td>
              <td><?php echo $row['RENTAL_RATE']; ?></td>
              <td><a href="payment.php?c=<?php echo $custId; ?>&i=<?php echo $row['INVENTORY_ID']; ?>" class="btn btn-info">Rent Movie</a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php } ?>
  </div>
</div><!--/.row-->
<script>
$(document).ready(function(){
  $('#movieTable').DataTable({
    responsive: true
  });
});
</script>
