<?php
require_once "validation.php";
$id=$_GET['id'];
if(isset($_SESSION['id'])){
  $query = "SELECT * FROM stories WHERE storyid=? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('i',$id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $page_id = $user['storyid'];
  $userID = $_SESSION['id'];
  $username = $_SESSION['username'];
  add_view($conn, $username, $page_id);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Story</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="container mt-5">
        <div class="row">
          <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo $user['title']; ?></h5>
              <p class="card-text"><?php echo $user['content']; ?></p>
            </div>
          </div>
          <div class="row" id="views">
            <div class="col-10">
              <p>Total Views <?php echo total_views($conn, $page_id); ?></p>
            </div>
            <div class="col-2">
              <p>Live Views <?php echo total_unique_views($conn, $page_id); ?></p>
            </div>
          </div>
          <a href="http://localhost/ReadCount/" class="btn btn-primary">Back</a>
        </div>
        </div>
  </div>
</body>
</html>
