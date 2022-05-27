<?php

$mysqli = new mysqli("localhost", "root", "1234", "wp");
$mysqli->begin_transaction();

try{
  $price = $_POST['price'];
  $period = $_POST['period'];
  $order_id = $_GET['order_id'];
  $id = $_GET['id'];
  $size = $_GET['size'];
  $user_id = $_SESSION['user_id'];

  $query = "select u.money as money from user as u where u.id = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $stmt->bind_result($money);
  $stmt->fetch();
  $stmt->close();

  if($money < $price) throw new InvalidArgumentException("금액이 부족합니다.");


  $query = "insert into bid(price, user_id, order_shoe_id, date, deadline) 
             values(?, ?, ?, now(), DATE_ADD(now(), INTERVAL ? DAY))";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iiii", $price, $user_id, $order_id, $period);
  $stmt->execute();
  $stmt->close();

  $query = "update user as u set u.money = u.money - ? WHERE u.id = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ii", $price, $user_id);
  $stmt->execute();
  $stmt->close();


  $mysqli->commit();

  echo "
  <script>
    alert('{$price} 원으로 입찰이 성사되었습니다.');
    location.href='..';
    </script>
  ";


} catch (mysqli_sql_exception $exception){
  $mysqli->rollback();
  echo "
  <script>
    alert('서버 상태가 좋지않습니다.');
    location.href='..';
    </script>
  ";
} catch (InvalidArgumentException $exception){
  $mysqli->rollback();
  echo "
  <script>
    alert('금액이 부족합니다.');
    location.href='..';
    </script>
  ";
}