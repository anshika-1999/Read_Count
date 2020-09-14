<?php

$errors = array();
$username = "";

define('DB_SERVER','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME','read_count');

$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

if($conn->connect_error){
  die("Error: Cannot connect to database" . $conn->connect_error);
}


?>
