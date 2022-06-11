<?php

require("../conf/conf_db.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_database);
$mysqli->begin_transaction();

if(isset($_POST['immediate'])){
  try{
    $id = $_GET['id'];
    $size = $_GET['size'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT b.id, b.price, b.order_shoe_id, b.user_id
                FROM bid as b
                LEFT JOIN order_shoe as os on b.order_shoe_id = os.id
                WHERE os.size = ? && os.shoe_id = ? && b.is_success = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= b.deadline
                ORDER BY b.price DESC
                LIMIT 1";


    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $size, $id);
    $stmt->execute();
    $stmt->bind_result($sell_id, $price, $order_shoe_id, $bidded_user_id);
    $stmt->fetch();
    $stmt->close();

    $query = "select u.money as money from user as u where u.id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($money);
    $stmt->fetch();
    $stmt->close();

    $query = "update user as u set u.money = u.money + ? WHERE u.id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii",$price, $user_id);
    $stmt->execute();
    $stmt->close();

    $query = "update bid as b set b.is_success = 1 WHERE b.id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i",$sell_id);
    $stmt->execute();
    $stmt->close();

    $query = "insert into sell(price, user_id, is_sold, order_shoe_id, date, deadline) 
             values(?, ?, 1, ?, now(), DATE_ADD(now(), INTERVAL 1 DAY))";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iii", $price, $user_id, $order_shoe_id);
    $stmt->execute();
    $stmt->close();

    $mysqli->commit();

    echo "
  <script>
    alert('{$price} 원으로 판매되었습니다.');
    window.location.replace('../price/detail.php?id={$id}');
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

    $query = "SELECT b.id, b.price, b.order_shoe_id, b.user_id
                FROM bid as b
                LEFT JOIN order_shoe as os on b.order_shoe_id = os.id
                WHERE os.size = ? && os.shoe_id = ? && b.is_success = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= b.deadline
                ORDER BY b.price DESC
                LIMIT 1";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $size, $id);
    $stmt->execute();
    $stmt->bind_result($bid_id, $max_price, $order_shoe_id, $bidded_user_id);
    $stmt->fetch();
    $stmt->close();

    if($max_price >= $price){
      $query = "update bid as b set b.is_success = 1 WHERE b.id = ?";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("i",$bid_id);
      $stmt->execute();
      $stmt->close();

      $query = "insert into sell(price, user_id, is_sold, order_shoe_id, date, deadline) 
             values(?, ?, 1, ?, now(), DATE_ADD(now(), INTERVAL 1 DAY))";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("iii", $max_price, $user_id, $order_shoe_id);
      $stmt->execute();
      $stmt->close();

      $query = "update user as u set u.money = u.money + ? WHERE u.id = ?";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("ii", $max_price, $user_id);
      $stmt->execute();
      $stmt->close();

      $mysqli->commit();
      echo "
      <script>
        alert('{$max_price} 원으로 판매가 성사되었습니다.');
        location.href='..';
        </script>
        ";
    } else {
      $query = "insert into sell(price, user_id, order_shoe_id, date, deadline) 
             values(?, ?, ?, now(), DATE_ADD(now(), INTERVAL ? DAY))";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("iiii", $price, $user_id, $order_id, $period);
      $stmt->execute();
      $stmt->close();

      $mysqli->commit();
      echo "
          <script>
            alert('{$price} 원으로 판매를 등록하였습니다.');
            window.location.replace('../price/detail.php?id={$id}');
            </script>
          ";
    }

  } catch (mysqli_sql_exception $exception) {
    $mysqli->rollback();
    header('HTTP/1.1 503 Service Unavailable');
    echo "
  <script>
    alert('서버 상태가 좋지않습니다.');
    location.href='..';
    </script>
  ";
  } catch (InvalidArgumentException $exception) {
    $mysqli->rollback();
    header('HTTP/1.1 400 Bad Request');
    echo "
  <script>
    alert('금액이 부족합니다.');
    location.href='..';
    </script>
  ";
  }
}