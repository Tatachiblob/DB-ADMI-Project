<?php
require_once ('../mysqlConnect.php');
$movieDetail = $conn->query("SELECT 		F.FILM_ID, F.TITLE, F.DESCRIPTION, F.RELEASE_YEAR, L.LANGUAGE_ID, L.NAME, L2.LANGUAGE_ID AS ORIGLANGID, L2.NAME AS ORIGLANG, F.RENTAL_DURATION, F.RENTAL_RATE, F.LENGTH, F.REPLACEMENT_COST, F.RATING, F.SPECIAL_FEATURES
                             FROM       FILM F
                             JOIN       LANGUAGE L ON F.LANGUAGE_ID = L.LANGUAGE_ID
                             LEFT JOIN  LANGUAGE L2 ON F.ORIGINAL_LANGUAGE_ID = L2.LANGUAGE_ID
                             WHERE      F.FILM_ID = {$_POST['id']};");
while($row = $movieDetail->fetch_assoc()){
  $filmId = $row['FILM_ID'];
  $title = $row['TITLE'];
  $desc = $row['DESCRIPTION'];
  $releaseYear = $row['RELEASE_YEAR'];
  $langId = $row['LANGUAGE_ID'];
  $origLangId = $row['ORIGLANGID'];
  $rentalDuration = $row['RENTAL_DURATION'];
  $rentalRate = $row['RENTAL_RATE'];
  $movieLenght = $row['LENGTH'];
  $replacementCost = $row['REPLACEMENT_COST'];
  $rating = $row['RATING'];
  $features = $row['SPECIAL_FEATURES'];
}
$langRs = $conn->query("SELECT LANGUAGE_ID, NAME FROM LANGUAGE ORDER BY 2");
$langRs2 = $conn->query("SELECT LANGUAGE_ID, NAME FROM LANGUAGE ORDER BY 2");
?>
<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading"><strong>New Customer Form</strong></div>
      <div class="panel-body">
        <div class="col-lg-12">
          <form action="editMovie.php" method="post">
            <input type="hidden" name="filmId" value="<?php echo $_POST['id']; ?>">
            <div class="form-group">
              <label>Movie Title</label>
              <input type="text" name="title" class="form-control" placeholder="Title" autofocus required value="<?php echo $title; ?>">
              <p class="help-block">Required</p>
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea name="desc" class="form-control" placeholder="Desciption" rows="3"><?php echo $desc; ?></textarea>
            </div>
            <div class="form-group">
              <label>Release Year</label>
              <input type="number" name="releaseYear" min="0" class="form-control" placeholder="Release Year" value="<?php echo $releaseYear; ?>">
            </div>
            <div class="form-group">
              <label>Movie Language</label>
              <select class="form-control" name="langId" required>
                <option value="">Choose Option</option>
                <?php while($row = $langRs->fetch_assoc()){
                  if($row['LANGUAGE_ID'] == $langId){
                    echo "<option value=\"{$row['LANGUAGE_ID']}\" selected>{$row['NAME']}</option>";
                  }else{
                    echo "<option value=\"{$row['LANGUAGE_ID']}\">{$row['NAME']}</option>";
                  }
               } ?>
              </select>
              <p class="help-block">Required</p>
            </div>
            <div class="form-group">
              <label>Original Movie Language</label>
              <select class="form-control" name="origLangId">
                <option value="">Choose Option</option>
                <?php while($row = $langRs2->fetch_assoc()){
                  if($row['LANGUAGE_ID'] == $origLangId){
                    echo "<option value=\"{$row['LANGUAGE_ID']}\" selected>{$row['NAME']}</option>";
                  }else{
                    echo "<option value=\"{$row['LANGUAGE_ID']}\">{$row['NAME']}</option>";
                  }
                } ?>
              </select>
            </div>
            <div class="form-group">
              <label>Rental Duration</label>
              <input name="rentalDuration" type="number" min="0" class="form-control" placeholder="Rental Duration" value="<?php echo $rentalDuration; ?>">
              <p class="help-block">Default Value is 3</p>
            </div>
            <div class="form-group">
              <label>Rental Rate</label>
              <input name="rentalRate" type="number" min="0" step="0.01" class="form-control" placeholder="Rental Rate" value="<?php echo $rentalRate; ?>">
              <p class="help-block">Default Value is 4.99</p>
            </div>
            <div class="form-group">
                <label>Movie Length</label>
                <input name="movieLength" type="number" class="form-control" placeholder="Movie Length" value="<?php echo $movieLenght; ?>">
            </div>
            <div class="form-group">
              <label>Replacement Cost</label>
              <input name="replacementCost" type="number" min="0" step="0.01" class="form-control" placeholder="Replacement Cost" value="<?php echo $replacementCost; ?>">
              <p>Default Value is 19.99</p>
            </div>
            <div class="form-group">
              <label>Movie Rating</label>
              <label class="checkbox-inline">
                <?php if($rating == "G"){ ?>
                <input type="radio" name="movieRating" value="G" checked>G
                <?php }else{ ?>
                <input type="radio" name="movieRating" value="G">G
                <?php } ?>
              </label>
              <label class="checkbox-inline">
                <?php if($rating == "PG"){ ?>
                <input type="radio" name="movieRating" value="PG" checked>PG
                <?php }else{ ?>
                <input type="radio" name="movieRating" value="PG">PG
                <?php } ?>
              </label>
              <label class="checkbox-inline">
                <?php if($rating == "PG-13"){ ?>
                <input type="radio" name="movieRating" value="PG-13" checked>PG-13
                <?php }else{ ?>
                <input type="radio" name="movieRating" value="PG-13">PG-13
                <?php } ?>
              </label>
              <label class="checkbox-inline">
                <?php if($rating == "R"){ ?>
                <input type="radio" name="movieRating" value="R" checked>R
                <?php }else{ ?>
                <input type="radio" name="movieRating" value="R">R
                <?php } ?>
              </label>
              <label class="checkbox-inline">
                <?php if($rating == "NC-17"){ ?>
                <input type="radio" name="movieRating" value="NC-17" checked>NC-17
                <?php }else{ ?>
                <input type="radio" name="movieRating" value="NC-17">NC-17
                <?php } ?>
              </label>
            </div>
            <div class="form-group">
              <label>Special Features</label>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="specialFeature[]" value="Trailers" checked> Trailers
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
        </div>
      </div><!--/.panel-body-->
    </div><!--/.panel panel-info-->
  </div><!--/.col-lg-6-->
</div><!--/.row-->
