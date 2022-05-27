<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
</head>
<body>

<?php include '../header.php'; ?>
<?php

  if(!isset($_SESSION['email'])){
    echo "
    <script>
      alert('로그인 해주세요');
      location.href='..';
      </script>
    ";
  }

  $id = $_GET['id'];
  $size = $_GET['size'];

  $con = mysqli_connect("localhost", "root", "1234", "wp")
  or die("Can't access DB");

  $query = "SELECT sh.id as id, sh.photo as photo, sh.name as name, b.name as brand, os.id as order_id
                     FROM shoe as sh
                     LEFT JOIN brand as b on sh.brand_id = b.id
                     LEFT JOIN order_shoe as os on os.shoe_id = {$id}
                     WHERE sh.id = {$id} && os.size = {$size}";

  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_array($result);
?>

<div class="container">
  <img src="../<?= $row['photo']?>">
  <div>
    <?= $row['brand']?>
  </div>
  <div>
    <?= $row['name']?>
  </div>
  <form action="./bid_action.php?id=<?=$id?>&size=<?=$size?>&order_id=<?=$row['order_id']?>" method="post">
    <label>
      구매 희망가
      <input type="number" name="price">
    </label>
    <label>
      입찰 마감기한
      <input type="number" name="period">
    </label>
    <input type="submit" value="submit">
  </form>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
