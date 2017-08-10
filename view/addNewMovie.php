<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
    header("Location: login.php");
}
$langRs = $conn->query("SELECT LANGUAGE_ID, NAME FROM LANGUAGE ORDER BY 2");
$langRs2 = $conn->query("SELECT LANGUAGE_ID, NAME FROM LANGUAGE ORDER BY 2");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add New Movie</title>
  <?php include '..\/styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">New Movie</h1>
        </div>
      </div><!--/.row-->
      <?php if(isset($_GET['error'])){ ?>
      <?php if($_GET['error'] = "accessLink"){ ?>
      <div class="row">
        <div class="alert alert-danger">Please Enter Movie Details</div>
      </div>
      <?php }} ?>
      <?php if(isset($_GET['e'])){ ?>
      <div class="row">
        <div class="alert alert-danger"><?php echo $_GET['e']; ?></div>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-lg-6">
          <div class="panel panel-info">
            <div class="panel-heading"><strong>Movie Entry Form</strong></div>
            <div class="panel-body">
              <div class="col-lg-12">
                <form action="addMovieLogic.php" method="post">
                  <div class="form-group">
                    <label>Movie Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Title" autofocus required>
                    <p class="help-block">Required</p>
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea name="desc" class="form-control" placeholder="Desciption" rows="3"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Release Year</label>
                    <input type="number" name="releaseYear" min="0" class="form-control" placeholder="Release Year">
                  </div>
                  <div class="form-group">
                    <label>Movie Language</label>
                    <select class="form-control" name="langId" required>
                      <option value="">Choose Option</option>
                      <?php while($row = $langRs->fetch_assoc()){ ?>
                      <option value="<?php echo $row['LANGUAGE_ID']; ?>"><?php echo $row['NAME']; ?></option>
                      <?php } ?>
                    </select>
                    <p class="help-block">Required</p>
                  </div>
                  <div class="form-group">
                    <label>Original Movie Language</label>
                    <select class="form-control" name="origLangId">
                      <option value="">Choose Option</option>
                      <?php while($row = $langRs2->fetch_assoc()){ ?>
                      <option value="<?php echo $row['LANGUAGE_ID']; ?>"><?php echo $row['NAME']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Rental Duration</label>
                    <input name="rentalDuration" type="number" min="0" class="form-control" placeholder="Rental Duration">
                    <p class="help-block">Default Value is 3</p>
                  </div>
                  <div class="form-group">
                    <label>Rental Rate</label>
                    <input name="rentalRate" type="number" min="0" step="0.01" class="form-control" placeholder="Rental Rate">
                    <p class="help-block">Default Value is 4.99</p>
                  </div>
                  <div class="form-group">
                      <label>Movie Length</label>
                      <input name="movieLength" type="number" class="form-control" placeholder="Movie Length">
                  </div>
                  <div class="form-group">
                    <label>Replacement Cost</label>
                    <input name="replacementCost" type="number" min="0" step="0.01" class="form-control" placeholder="Replacement Cost">
                    <p>Default Value is 19.99</p>
                  </div>
                  <div class="form-group">
                    <label>Movie Rating</label>
                    <label class="checkbox-inline">
                      <input type="radio" name="movieRating" value="G" checked>G
                    </label>
                    <label class="checkbox-inline">
                      <input type="radio" name="movieRating" value="PG">PG
                    </label>
                    <label class="checkbox-inline">
                      <input type="radio" name="movieRating" value="PG-13">PG-13
                    </label>
                    <label class="checkbox-inline">
                      <input type="radio" name="movieRating" value="R">R
                    </label>
                    <label class="checkbox-inline">
                      <input type="radio" name="movieRating" value="NC-17">NC-17
                    </label>
                  </div>
                  <div class="form-group">
                    <label>Special Features</label>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="specialFeature[]" value="Trailers"> Trailers
                      </label>
                    </div>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="specialFeature[]" value="Commentaries"> Commentaries
                      </label>
                    </div>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="specialFeature[]" value="Deleted Scenes"> Deleted Scenes
                      </label>
                    </div>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="specialFeature[]" value="Behind the Scenes"> Behind the Scenes
                      </label>
                    </div>
                  </div>
                  <input type="submit" value="Submit" class="btn btn-success">
                  <input type="reset" value="Reset" class="btn btn-warning">
                </form><!--FORM-->
              </div><!--/.col-lg-6-->
            </div>
          </div>
        </div>
      </div><!--/.row-->
    </div>
  </div>
</body>
</html>
