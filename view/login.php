<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
  <?php include '..\/styleInclude.html'; ?>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
          <div class="panel-heading"><h3 class="panel-title">Please Sign In</h3></div>
          <div class="panel-body">
            <form action="#" method="post">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" name="email" type="text" autofocus required>
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Password" name="password" type="password" required>
                </div>
                <input type="submit" value="Login" class="btn btn-lg btn-success btn-block">
              </fieldset>
            </form>
          </div><!--/.panel-body-->
        </div><!--/.login-panel-->
      </div><!--/.col-md-4 col-md-->
    </div><!--/.row-->
  </div><!--/.container-->
</body>
</html
