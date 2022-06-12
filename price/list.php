<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/price.css" rel="stylesheet">
  <link href="/css/price/list.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <title>ShoeKream</title>
  <link rel="shortcut icon" href="../img/fav.png">
</head>
<body>

<?php include 'list_header.php'; ?>

<?php

require("../conf/conf_db.php");
if(isset($_SESSION['size'])){
  $size = $_SESSION['size'];
} else {
  $size = 260;
}
$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database)
or die("Can't access DB");

$query = "SELECT sh.id as id, sh.photo as photo, sh.name as name, b.name as brand,
                 (
                    SELECT s.price
                        FROM sell as s
                        LEFT JOIN order_shoe as os on s.order_shoe_id = os.id
                        WHERE os.size = {$size} && os.shoe_id = sh.id && s.is_sold = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= s.deadline
                        ORDER BY s.price ASC
                        LIMIT 1
                 ) as price,
                 (
                    select count(*)
                      from Wish
                      where shoe_id = sh.id
                 ) as wish_cnt  
                 FROM shoe as sh
                 LEFT JOIN brand as b on sh.brand_id = b.id 
                 ";

if(isset($_GET['brand'])){
  $query = $query." where b.name = '{$_GET['brand']}' ";
}

if(!isset($_GET['sort']) || $_GET['sort'] == 'new'){
  $query = $query." ORDER BY sh.id desc";
} elseif($_GET['sort'] == 'pop'){
  $query = $query." ORDER BY wish_cnt desc, sh.id desc";
}


$result = mysqli_query($con, $query);
$list_cnt = mysqli_num_rows($result);
?>

<div class="container_list">
  <div class="product_list_wrap">
    <div class="product_list">
      <?php
      for($i = 0; $i < $list_cnt; $i++){
        $row = mysqli_fetch_array($result);
        if($row['price'] == null){
          $row['price'] = '-';
        }
        $color = colorDistributer($row['id'] % 4);
        echo "
                <div class='product_item'>
                  <a href='/price/detail.php?id={$row['id']}' class='item_inner'>
                    <div class='thumb_box'>
                      <div class='product' style='background: {$color}'>
                        <img src='../{$row['photo']}'>
                      </div>
                    </div>
                    <div class='info_box'>
                      <div class='brand'>
                        <p class='brand_text'>{$row['brand']}</p>
                      </div>
                      <p class='name'>{$row['name']}</p>
                      <div class='price'>
                        <div class='amount'>
                          <em class='num'>{$row['price']}</em>
                          <span class='won'>원</span>
                        </div>
                        <div class='desc'>
                          <p>즉시 구매가</p>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              ";
      }
      ?>

    </div>
  </div>
</div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
