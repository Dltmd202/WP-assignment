<?php

$mysqli = new mysqli("localhost", "root", "1234", "wp");
$mysqli->begin_transaction();

try{
  $email = $_POST['email'];
  $password = $_POST['password'];
  $size = $_POST['size'];


  $query = "insert into user(email, password, shoe_size, money)
            values(?, ?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ssi",$email, $password, $user_id);
  $stmt->execute();
  $stmt->close();

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

