<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
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

$query = "SELECT sh.id as id, sh.photo as photo, sh.name as name, b.name as brand, os.id as order_id,
                   (
                      SELECT b.price
                          FROM bid as b
                          LEFT JOIN order_shoe as os on b.order_shoe_id = os.id
                          WHERE os.size = {$size} && os.shoe_id = {$id} && b.is_success = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= b.deadline
                          ORDER BY b.price DESC
                          LIMIT 1
                   ) as direct_selling_price
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
  <form action="./sell_action.php?id=<?=$id?>&size=<?=$size?>&order_id=<?=$row['order_id']?>" method="post">
    <div
      <?php
      if(!isset($row['direct_selling_price'])){
        echo "style='display: none'";
      }
      ?>
    >
      <label>
        <input type="checkbox" name="immediate"
               value="immediate" id="immediate_check" onchange="isImmediatelyPurchaseChecked(this)">
        <span id="min_price"><?=$row['direct_selling_price']?></span>
        원에 즉시 판매하기
      </label>
    </div>
    <div>
      <label>
        판매 희망가
        <input type="number" name="price" id="price" onchange="priceChanged(<?=$row['direct_selling_price']?>)">
      </label>
      <label>
        입찰 마감기한
        <input type="number" name="period" id="period">
      </label>
      <input type="submit" value="submit">
    </div>
  </form>
</div>


<?php include '../footer.php'; ?>
<script src="../js/sell.js"></script>
</body>
</html>
