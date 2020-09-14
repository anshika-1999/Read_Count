<?php
require_once 'validation.php';
if(!isset($_SESSION['id'])) {
  header('location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Homepage</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="container mt-5">
        <?php if(isset($_SESSION['message'])): ?>
          <div class="alert <?php echo $_SESSION['alert-class']; ?>">
            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            unset($_SESSION['alert-class']);
            ?>
          </div>
        <?php endif; ?>
        <div class="row">
          <div class="col-10">
            <h3>Welcome, <?php echo $_SESSION['username']; ?>!</h3>
          </div>
          <div class="col-2">
            <button class="btn btn-danger"><a href="index.php?logout=1" class="logout">Logout</a></button>
          </div>
        </div>
        <br>
        <?php
        $result=getStory($conn);
        while($row=$result->fetch_assoc()){
          component($row['title'],$row['content'],$row['storyid']);
        }
        ?>

  </div>
</body>
</html>
