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
  <title>Edit Movie Details</title>
  <?php include '..\/styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Editing Movie</h1>
        </div>
      </div><!--/.row-->
      <?php if(isset($_GET['msg'])){ ?>
      <div class="row">
        <div class="alert alert-success">Movie Edited</div>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-lg-4">
          <div class="panel panel-info">
            <div class="panel-heading">Movie Selection</div>
            <div class="panel-body">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Movie</label>
                  <select name="movie" class="form-control" required onchange="movieDetails('mDetails.php', this.value)">
                    <option value="">Choose a Movie</option>
                    <?php while($row = $movieRs->fetch_assoc()){
                    echo "<option value=\"{$row['FILM_ID']}\">{$row['TITLE']}</option>";
                    } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!--/.row-->
      <div id="ajaxMovie">
      </div><!--/#ajaxMovie-->
    </div>
  </div><!--/#wrapper-->
</body>
<script>
function movieDetails(ajaxPage, movie){
  $.ajax({
    type: "POST",
    url: ajaxPage,
    data: "id="+movie,
    dataType: "html",
    success: function(result){
      $('#ajaxMovie').html(result);
    }
  });
}
</script>
</html>
