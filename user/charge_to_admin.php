<?php
require("../conf/conf_db.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_database);
$mysqli->begin_transaction();

if(!isset($_SESSION['email'])){
  echo "
    <script>
      alert('로그인 해주세요');
      location.href='..';
      </script>
    ";
}
$money = 100000;
if($_SESSION['authority'] == "ADMIN"){
  $money = 1000000;
}

$user_id = $_SESSION['user_id'];

$query = "update user as u set u.money = u.money + ? WHERE u.id = ?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $money, $user_id);
$stmt->execute();
$stmt->close();

$mysqli->commit();

if($_SESSION['authority'] == "ADMIN"){
  echo "
      <script>
        alert('관리자 등급으로 확인되어 1,000,000원이 충전되었습니다.')
        window.location.replace('./mypage.php');
        </script>
      ";
} else {
  echo "
      <script>
        alert('서비스 체험을 위한 100,000원이 충전되었습니다.')
        window.location.replace('./mypage.php');
        </script>
      ";
}