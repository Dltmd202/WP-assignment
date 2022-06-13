<?php
require("../conf/conf_db.php");
$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database)
or die("Can't access DB");

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT u.email as email, au.tag as autag, u.shoe_size as shsize, u.id as id
            FROM user as u
            LEFT JOIN Authority as au on u.authority_id = au.id
            WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($con, $query);

$row = mysqli_fetch_array($result);

if ($row != null) {
  $_SESSION['email'] = $row['email'];
  $_SESSION['authority'] = $row['autag'];
  $_SESSION['size'] = $row['shsize'];
  $_SESSION['user_id'] = $row['id'];
  echo "<script>alert('로그인에 성공하였습니다.')</script>";
  echo "<script>location.replace('../index.php');</script>";
  exit;
}

echo "<script>alert('Invalid username or password')</script>";
echo "<script>location.replace('login.php');</script>";
exit;

?>