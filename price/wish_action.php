<?php

$id = $_GET['id'];
if(!isset($_SESSION['email'])){
  echo "
    <script>
      alert('로그인 해주세요');
      window.location.replace('../user/login.php');
      </script>
    ";
}else {
  $user_id = $_SESSION['user_id'];

  require("../conf/conf_db.php");
  $mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_database);
  $mysqli->begin_transaction();

  $query = "select w.id
              from Wish as w
              where shoe_id = ? && user_id = ?";

  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ii", $id, $user_id);
  $stmt->execute();
  $stmt->bind_result($wish_id);
  $stmt->fetch();
  $stmt->close();

  if(!isset($wish_id)){
    $query = "insert into wish(shoe_id, user_id, dtime)
      values(?, ?, now())";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();

    $mysqli->commit();

    echo "
    <script>
      window.location.replace('./detail.php?id={$id}');
    </script>
  ";
  } else {
    $query = "delete
                from wish
                where shoe_id = ? && user_id = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();

    $mysqli->commit();

    echo "
      <script>
        window.location.replace('./detail.php?id={$id}');
      </script>
    ";
  }
}