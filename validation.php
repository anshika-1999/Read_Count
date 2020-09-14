<?php

session_start();
require 'connect.php';

if(isset($_POST['register'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];
  if(empty($username)){
    $errors['username'] = "Username Required";
  }
  if(empty($password)){
    $errors['password'] = "Password Required";
  }
  if($password !== $confirmPassword){
    $errors['password'] = "The two passwords do not match";
  }

  $query = "SELECT * FROM users WHERE username=? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s',$username);
  $stmt->execute();
  $result = $stmt->get_result();
  $userCount = $result->num_rows;
  $stmt->close();

  if($userCount > 0){
    $errors['username'] = "Username already exists";
  }
  if(count($errors)===0){
    $password = password_hash($password,PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss' , $username, $password);
    if($stmt->execute()){
      $user_id = $conn->insert_id;
      $_SESSION['id'] = $user_id;
      $_SESSION['username'] = $username;
      $_SESSION['message'] = "You are now logged in!";
      $_SESSION['alert-class'] = "alert-success";
      header('location: index.php');
      exit();
    } else {
      $errors['db_error'] = "Database error: Failed to register";
    }
  }

}
if(isset($_POST['login'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
  if(empty($username)){
    $errors['username'] = "Username Required";
  }
  if(empty($password)){
    $errors['password'] = "Password Required";
  }

  if(count($errors)===0){
    $query = "SELECT * FROM users WHERE username=? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if(password_verify($password,$user['password'])){
      $_SESSION['id'] = $user['user_id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['message'] = "You are now logged in!";
      $_SESSION['alert-class'] = "alert-success";
      header('location: index.php');
      exit();
    } else {
      $errors['login_fail'] = "Wrong Credentials";
    }
  }

}

if(isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['id']);
  unset($_SESSION['username']);
  header('location: login.php');
  exit();
}

function component($title,$content,$storyid){
  $element = '<br><div class="row">
    <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">'.$title.'</h5>
        <p class="card-text">'.substr($content,0,200).'..........</p>
        <a href="http://localhost/ReadCount/storydetail.php?id='.$storyid.'" class="btn btn-primary">Explore</a>
      </div>
    </div>
  </div>
  </div>';
  echo $element;
}

function getStory($conn){
  $sql = "SELECT * FROM stories";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $count = $result->num_rows;
  if($count>0){
    return $result;
  }
}

function total_views($conn, $page_id){
  $query = "SELECT total_views FROM pages WHERE page_id='$page_id'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result=$stmt->get_result();
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        return $row['total_views'];
      }
    }
  }



  function total_unique_views($conn,$page_id){
    $current_time=time();
    $timeout=$current_time-(60);
    $query="SELECT * FROM page_views WHERE time_s >? AND page_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii' , $timeout, $page_id);
    $stmt->execute();
    $result=$stmt->get_result();
    $count_visitors=$result->num_rows;
    return $count_visitors;
    setTimeout(total_unique_views($conn,$page_id), 15000);
  }

function is_unique_view($conn, $username, $page_id){
  $query = "SELECT * FROM page_views WHERE username='$username' AND page_id='$page_id'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result=$stmt->get_result();
  if($result->num_rows){
    return false;
  } else {
    return true;
  }
}

function add_view($conn, $username, $page_id){
  $current_time=time();
  $timeout=$current_time-(60);
  if(is_unique_view($conn, $username, $page_id) === true){
    $query = "INSERT INTO page_views (username, page_id, time_s) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sii' , $username, $page_id, $current_time);
    if($stmt->execute()){
      $sql = "SELECT * FROM pages WHERE page_id=?";
      $stmt2 = $conn->prepare($sql);
      $stmt2->bind_param('i' , $page_id);
      $stmt2->execute();
      $result=$stmt2->get_result();
      if($result->num_rows > 0){
        $query2 = "UPDATE pages SET total_views = total_views + 1 WHERE page_id=?";
        $stmt3 = $conn->prepare($query2);
        $stmt3->bind_param('i' , $page_id);
        if(!$stmt3->execute()){
          echo "Error updating record: " . mysqli_error($conn);
        }
      } else {
        $query3 = "INSERT INTO pages (page_id, total_views) VALUES (?, ?)";
        $stmt4 = $conn->prepare($query3);
        $num=1;
        $stmt4->bind_param('ii' , $page_id,$num);
        if(!$stmt4->execute()){
          echo "Error updating record: " . mysqli_error($conn);
        }
      }
    } else {
      echo "Error inserting record: " . mysqli_error($conn);
    }
  } else {
    $sql2="UPDATE page_views SET time_s='".time()."' WHERE username=?";
    $stmt5 = $conn->prepare($sql2);
    $stmt5->bind_param('s' , $username);
    $stmt5->execute();
  }
}


?>
