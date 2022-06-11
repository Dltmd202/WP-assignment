<?php
require("../conf/conf_db.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_database);
$mysqli->begin_transaction();

try{
  $email = $_POST['email'];
  $password = $_POST['password'];
  $size = $_POST['size'];


  $query = "insert into user(email, password, shoe_size, money)
            values(?, ?, ?, 0)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ssi",$email, $password, $size);
  $stmt->execute();
  $stmt->close();

  $mysqli->commit();

  echo "  
  <script>
      alert('회원가입이 완료되었습니다.');
      location.href='..';
  </script>";


} catch (mysqli_sql_exception $exception){
  $mysqli->rollback();
  echo "
  <script>
    alert('이미 존재하는 이메일 입니다.');
    location.href='..';
    </script>
  ";
}

