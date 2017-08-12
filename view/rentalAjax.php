<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
$returnSql = "SELECT      R.RENTAL_ID, I.INVENTORY_ID, F.FILM_ID, F.TITLE, R.RENTAL_DATE,
                          ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION) AS DATEDUE,
                          CASE
                            WHEN DATEDIFF(NOW(), ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION)) < 0 THEN 0
                            ELSE ROUND(CEILING(DATEDIFF(NOW(), ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION)) / F.RENTAL_DURATION) * F.RENTAL_RATE, 2)
                          END AS PENALTIES
              FROM        FILM F
              JOIN        INVENTORY I ON F.FILM_ID = I.FILM_ID
              JOIN        RENTAL R ON I.INVENTORY_ID = R.INVENTORY_ID
              WHERE       R.RETURN_DATE IS NULL AND R.CUSTOMER_ID = {$_POST['id']};";

$returnRs = $conn->query($returnSql);
?>
<div class="row">
  <div class="col-lg-7">
    <div class="panel panel-info">
      <div class="panel-heading">Movies For Returning</div>
      <div class="panel-body">
        <table width="100%" class="table table-striped table-bordered table-hover" id="movieTable">
          <thead>
            <tr>
              <th>Invenotry</th>
              <th>Film Title</th>
              <th>Rent Date</th>
              <th>Date Due</th>
              <th>Penalty</th>
              <th>Return Movie</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $returnRs->fetch_assoc()){ ?>
            <tr>
              <td><?php echo $row['INVENTORY_ID']; ?></td>
              <td><?php echo $row['TITLE']; ?></td>
              <td><?php echo $row['RENTAL_DATE']; ?></td>
              <td><?php echo $row['DATEDUE']; ?></td>
              <td><?php echo $row['PENALTIES']; ?></td>
              <td align="center"><a href="returnPayment.php?i=<?php echo $row['RENTAL_ID']; ?>&c=<?php echo $_POST['id']; ?>&f=<?php echo $row['FILM_ID']; ?>" class="btn btn-info">Return Movie</a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div><!--/.row-->
