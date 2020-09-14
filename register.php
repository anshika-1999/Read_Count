<?php require_once 'validation.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4 form-div">
        <form action="register.php" method="post">
          <h3 class="text-center">Register</h3>
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
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control form-control-lg">
          </div>
          <div class="form-group">
            <button type="submit" name="register" class="btn btn-primary btn-block btn-lg">Register</button>
          </div>
          <p class="text-center">Already an user? <a href="login.php">Sign In</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
