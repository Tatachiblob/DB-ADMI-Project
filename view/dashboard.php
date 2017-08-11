<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
$movieRs = $conn->query("SELECT FILM_ID, TITLE FROM FILM ORDER BY 2;");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Main Menu</title>
  <?php include '..\/styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Dashboard</h1>
        </div>
      </div><!--/.row-->
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-user-plus fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">Registration</div>
                </div>
              </div>
            </div>
            <a href="customerRegistration.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div><!--/.col-lg-3-->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-plus-circle fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">Add Movie</div>
                </div>
              </div>
            </div>
            <a href="addNewMovie.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div><!--/.col-lg-3-->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-film fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">Modify Movie</div>
                </div>
              </div>
            </div>
            <!--<a href="addActor.php">-->
            <div id="modalPop" onclick="openModal('#myModal')">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </div>
            <!--</a>-->
          </div>
        </div><!--/.col-lg-3-->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-edit fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">Edit Movie</div>
                </div>
              </div>
            </div>
            <a href="editFilm.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div><!--/.col-lg-3-->
      </div><!--/.row-->
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-plus fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">Modify Inventory</div>
                </div>
              </div>
            </div>
            <!--<a href="#">-->
            <div id="modalPop2" onclick="openModal('#myModal2')">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </div>
            <!--</a>-->
          </div>
        </div><!--/.col-lg-3-->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-credit-card fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">Renting</div>
                </div>
              </div>
            </div>
            <a href="rentMoviePage.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div><!--/.col-lg-3-->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-reply fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">Returning</div>
                </div>
              </div>
            </div>
            <a href="returnMoviePage.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div><!--/.col-lg-3-->
      </div><!--/.row-->
    </div><!--/#page-wrapper-->
  </div><!--/#wrapper-->
  <!-- Modal Content -->
  <div class="modal fade" id="myModal" role="dialog">
  	<div class="modal-dialog modal-dialog-center">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Select Movie</h4>
  			</div><!-- /.modal-header -->
  			<div class="modal-body">
  				<center>
            <form action="filmSelect.php?action=modify" method="post">
              <div class="form-group">
                <label>Choose Movie</label>
                <select name="movieId" class="form-control" required>
                  <option value="">Select</option>
                  <?php while($row = $movieRs->fetch_assoc()){ ?>
                  <option value="<?php echo $row['FILM_ID']; ?>"><?php echo $row['TITLE']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <input type="submit" value="Select" class="btn btn-success">
            </form>
  				</center>
  			</div><!-- /.modal-body -->
  		</div><!-- modal-content -->
  	</div><!-- /.modal-dialog modal-dialog-center -->
  </div><!-- /.modal -->
  <!-- End of Modal Content -->
  <!-- Modal Content -->
  <div class="modal fade" id="myModal2" role="dialog">
  	<div class="modal-dialog modal-dialog-center">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Select Movie</h4>
  			</div><!-- /.modal-header -->
  			<div class="modal-body">
  				<center>
            <a href="addInventory.php" class="btn btn-info">Add Inventory Copy</a>
            <a href="#" class="btn btn-info">Modify Inventory Copy</a>
  				</center>
  			</div><!-- /.modal-body -->
  		</div><!-- modal-content -->
  	</div><!-- /.modal-dialog modal-dialog-center -->
  </div><!-- /.modal -->
  <!-- End of Modal Content -->
</body>
<script>
function openModal(modelId){
  $(modelId).modal();
}
</script>
</html>
