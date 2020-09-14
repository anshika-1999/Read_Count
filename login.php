<?php require_once 'validation.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4 form-div login">
        <form action="login.php" method="post">
          <h3 class="text-center">Login</h3>
          <?php if(count($errors)>0): ?>
            <div class="alert alert-danger">
              <?php foreach($errors as $error): ?>
                <li><?php echo $error; ?></li>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>" class="form-control form-control-lg">
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control form-control-lg">
          </div>
          <div class="form-group">
            <button type="submit" name="login" class="btn btn-primary btn-block btn-lg">Login</button>
          </div>
          <p class="text-center">Not yet an user? <a href="register.php">Sign Up</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
