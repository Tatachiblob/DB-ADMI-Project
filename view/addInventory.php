<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
$movieSql = "SELECT		  F.FILM_ID, I.STORE_ID, F.TITLE, COUNT(I.INVENTORY_ID) AS COUNT
             FROM       FILM F
             LEFT JOIN	(SELECT * FROM INVENTORY WHERE STORE_ID = {$_SESSION['storeId']} AND STATUS IN (1, 2)) I ON F.FILM_ID = I.FILM_ID
             GROUP BY	  F.FILM_ID;";
$movieRs = $conn->query($movieSql);
?>
<!DOCTYPE html>
<html>
<head>
  <title> New Inventory Copy</title>
  <?php include '../styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Inventory Copies(Store <?php echo $_SESSION['storeId'] ?>)</h1>
        </div>
      </div><!--/.row-->
      <?php if(isset($_GET['success'])){ ?>
      <div class="row">
        <div class="col-lg-12">
          <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-info-circle"></i><strong><?php echo $_GET['success']; ?> Added into Inventory</strong>
          </div>
        </div>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-lg-5">
          <div class="panel panel-info">
            <div class="panel-heading"><strong>Available Movie Copies</strong></div>
            <div class="panel-body">
              <table width="100%" class="table table-striped table-bordered table-hover" id="movieTable">
                <thead>
                  <tr>
                    <th>Movie Title</th>
                    <th>Available Copies</th>
                    <th>Add Copy</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = $movieRs->fetch_assoc()){ ?>
                  <tr>
                    <td><?php echo $row['TITLE']; ?></td>
                    <td><?php echo $row['COUNT']; ?> Copies</td>
                    <td align="center"><a href="addCopyLogic.php?m=<?php echo $row['FILM_ID']; ?>&n=<?php echo $row['TITLE']; ?>" class="btn btn-info">Select</a></td>
                  </tr>
                <?php } ?>
              </tbody>
              </table>
            </div>
          </div>
        </div><!--/.col-lg-5-->
      </div><!--/.row-->
    </div><!--/#page-wrapper-->
  </div><!--/#wrapper-->
</body>
<script>
$(document).ready(function(){
  $('#movieTable').DataTable({
    responsive: true
  });
});
</script>
</html>
