<?php

$mysqli = new mysqli("localhost", "root", "1234", "wp");
$mysqli->begin_transaction();

if($_POST['immediate']){
  try{
    $id = $_GET['id'];
    $size = $_GET['size'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT s.id, s.price, s.order_shoe_id
                FROM sell as s
                LEFT JOIN order_shoe as os on s.order_shoe_id = os.id
                WHERE os.size = ? && os.shoe_id = ? && s.is_sold = 0 && now() <= s.deadline
                ORDER BY s.price DESC
                LIMIT 1";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $size, $id);
    $stmt->execute();
    $stmt->bind_result($sell_id, $price, $order_shoe_id);
    $stmt->fetch();
    $stmt->close();

    $query = "select u.money as money from user as u where u.id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($money);
    $stmt->fetch();
    $stmt->close();


    if($money < $price) {
      echo $price;
      echo "<br>";
      echo $money;
      throw new InvalidArgumentException("금액이 부족합니다.");
    }

    $query = "update sell as s set s.is_sold = 1 WHERE s.id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i",$sell_id);
    $stmt->execute();
    $stmt->close();

    $query = "insert into bid(price, user_id, is_success, order_shoe_id, date, deadline) 
             values(?, ?, 1, ?, now(), DATE_ADD(now(), INTERVAL 1 DAY))";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iii", $price, $user_id, $order_shoe_id);
    $stmt->execute();
    $stmt->close();

    $mysqli->commit();

    echo "
  <script>
    alert('{$price} 원으로 구매되었습니다.');
    location.href='..';
    </script>
  ";


  }catch (mysqli_sql_exception $exception){
    $mysqli->rollback();
    throw $exception;
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
//    location.href='..';
    </script>
  ";
  }
} else {

  try {
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

    if ($money < $price) throw new InvalidArgumentException("금액이 부족합니다.");

    $query = "SELECT s.id, s.price, s.order_shoe_id
                FROM sell as s
                LEFT JOIN order_shoe as os on s.order_shoe_id = os.id
                WHERE os.size = ? && os.shoe_id = ? && s.is_sold = 0 && now() <= s.deadline
                ORDER BY s.price DESC
                LIMIT 1";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $size, $id);
    $stmt->execute();
    $stmt->bind_result($sell_id, $min_price, $order_shoe_id);
    $stmt->fetch();
    $stmt->close();

    if($min_price <= $price){
      $query = "update sell as s set s.is_sold = 1 WHERE s.id = ?";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("i",$sell_id);
      $stmt->execute();
      $stmt->close();

      $query = "insert into bid(price, user_id, is_success, order_shoe_id, date, deadline) 
             values(?, ?, 1, ?, now(), DATE_ADD(now(), INTERVAL 1 DAY))";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("iii", $price, $user_id, $order_shoe_id);
      $stmt->execute();
      $stmt->close();
    } else {
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
    }


    $mysqli->commit();
    $price = min($price, $min_price);
    echo "
  <script>
    alert('{$price} 원으로 입찰이 성사되었습니다.');
    location.href='..';
    </script>
  ";


  } catch (mysqli_sql_exception $exception) {
    $mysqli->rollback();
    echo "
  <script>
    alert('서버 상태가 좋지않습니다.');
    location.href='..';
    </script>
  ";
  } catch (InvalidArgumentException $exception) {
    $mysqli->rollback();
    echo "
  <script>
    alert('금액이 부족합니다.');
    location.href='..';
    </script>
  ";
  }
}