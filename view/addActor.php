<?php
session_start();
require_once ('../mysqlConnect.php');
if(!isset($_SESSION['isLogin'])){
  header("Location: login.php");
}
if(!isset($_GET['id'])){
  header("Location: dashboard.php");
}else{
  $m = $conn->query("SELECT TITLE FROM FILM WHERE FILM_ID = {$_GET['id']}");
  while($row = $m->fetch_assoc()){
    $movieName = $row['TITLE'];
  }
  $movieActorSql = "SELECT		F.FILM_ID, F.TITLE, A.ACTOR_ID, A.FIRST_NAME, A.LAST_NAME
                    FROM      FILM F
                    JOIN      FILM_ACTOR FA ON F.FILM_ID = FA.FILM_ID
                    JOIN      ACTOR A ON FA.ACTOR_ID = A.ACTOR_ID
                    WHERE     F.FILM_ID = {$_GET['id']}
                    ORDER BY  2, 4, 5;";
  $actorSelectSql = "SELECT		ACTOR_ID, CONCAT(FIRST_NAME, ', ', LAST_NAME) AS NAME
                     FROM     ACTOR
                     WHERE		ACTOR_ID NOT IN (SELECT ACTOR_ID FROM FILM_ACTOR WHERE FILM_ID = {$_GET['id']})
                     ORDER BY FIRST_NAME, LAST_NAME;";
  $categorySql =    "SELECT   CATEGORY_ID, NAME
                     FROM     CATEGORY
                     WHERE    CATEGORY_ID NOT IN (SELECT CATEGORY_ID FROM FILM_CATEGORY WHERE FILM_ID = {$_GET['id']});";
  $filmCategory =   "SELECT   C.CATEGORY_ID, C.NAME
                     FROM     CATEGORY C
                     JOIN     FILM_CATEGORY FC ON C.CATEGORY_ID = FC.CATEGORY_ID
                     WHERE    FC.FILM_ID = {$_GET['id']};";
  $a = $conn->query($movieActorSql);
  $b = $conn->query($actorSelectSql);
  $c = $conn->query($categorySql);
  $d = $conn->query($filmCategory);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Movie Actor Modification</title>
  <?php include '../styleInclude.html'; ?>
</head>
<body>
  <div id="wrapper">
    <?php include 'navbarInclude.html'; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Movie Modification(<?php echo $movieName; ?>)</h1>
        </div>
      </div><!--/.row-->
      <div class="row">
        <div class="col-lg-5">
          <div class="panel panel-info">
            <div class="panel-heading"><strong>Add Actor</strong></div>
            <div class="panel-body">
              <div class="col-lg-12">
                <form action="actorLogic.php?action=add" method="post">
                  <input type="hidden" name="movieId" value="<?php echo $_GET['id']; ?>">
                  <div class="form-group">
                    <label>Actor</label>
                    <select name="actor" class="form-control" required>
                      <option value="">Choose Actor</option>
                      <?php while($row = $b->fetch_assoc()){ ?>
                      <option value="<?php echo $row['ACTOR_ID']; ?>"><?php echo $row['NAME']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <input type="submit" value="Add Actor" class="btn btn-success">
                </form>
              </div><!--/.col-lg-12-->
            </div><!--/.panel-body-->
          </div><!--/.panel-->
        </div><!--/.col-lg-5-->
        <div class="col-lg-5">
          <div class="panel panel-info">
            <div class="panel-heading"><strong>Add Category</strong></div>
            <div class="panel-body">
              <div class="col-lg-12">
                <form action="categoryLogic.php?action=add" method="post">
                  <input type="hidden" name="movieId" value="<?php echo $_GET['id']; ?>">
                  <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control" required>
                      <option value="">Choose Category</option>
                      <?php while($row = $c->fetch_assoc()){ ?>
                      <option value="<?php echo $row['CATEGORY_ID']; ?>"><?php echo $row['NAME']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <input type="submit" value="Add Actor" class="btn btn-success">
                </form>
              </div><!--/.col-lg-12-->
            </div><!--/.panel-body-->
          </div><!--/.panel-->
        </div><!--/.col-lg-5-->
      </div><!--/.row-->
      <div class="row">
        <div class="col-lg-5">
          <div class="panel panel-default">
            <div class="panel-heading"><strong>Existing Actors</strong></div>
            <div class="panel-body">
              <table width="100%" class="table table-striped table-bordered table-hover" id="actorTable">
                <thead>
                  <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = $a->fetch_assoc()){ ?>
                  <tr>
                    <?php
                    echo "
                    <td>{$row['FIRST_NAME']}</td>
                    <td>{$row['LAST_NAME']}</td>
                    ";
                    ?>
                    <td align="center"><a href="actorLogic.php?m=<?php echo $_GET['id']; ?>&action=d&a=<?php echo $row['ACTOR_ID']; ?>" class="btn btn-warning">Remove</a></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="panel-footer">
              End of Actor Table
            </div>
          </div>
        </div><!--/.col-lg-5-->
        <div class="col-lg-5">
          <div class="panel panel-default">
            <div class="panel-heading">Existing Categories</div>
            <div class="panel-body">
              <table width="100%" class="table table-striped table-bordered table-hover" id="categoryTable">
                <thead>
                  <tr>
                    <th>Category Name</th>
                    <th>Delete Category</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = $d->fetch_assoc()){ ?>
                  <tr>
                    <td><?php echo $row['NAME']; ?></td>
                    <td align="center"><a href="categoryLogic.php?action=d&m=<?php echo $_GET['id']; ?>&c=<?php echo $row['CATEGORY_ID']; ?>" class="btn btn-warning">Remove</a></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="panel-footer">
              End of Category
            </div>
          </div>
        </div>
      </div><!--/.row-->
    </div><!--/#page-wrapper-->
  </div><!--/#wrapper-->
</body>
<script>
$(document).ready(function(){
  $("#actorTable").DataTable({
    responsive: true
  });
  $("#categoryTable").DataTable({
    responsive: true
  });
});
</script>
</html>
