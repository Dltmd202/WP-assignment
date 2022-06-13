<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/user/user.css"/>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <link href="/css/user/user.css" rel="stylesheet">
  <link href="/css/user/wish.css" rel="stylesheet">
  <title>ShoeKream</title>
  <link rel="shortcut icon" href="../img/fav.png">
</head>
<body>

<?php include '../header.php'; ?>
<?php

if(!isset($_SESSION['user_id'])){
  echo "
    <script>
      alert('로그인 해주세요');
      location.href='..';
      </script>
    ";
}
require("../conf/conf_db.php");
$user_id = $_SESSION['user_id'];
$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database)
or die("Can't access DB");

$query = "SELECT * from user as u 
                LEFT JOIN Authority as au on u.authority_id = au.id
                where u.id = {$user_id}";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_array($result);


$query = "select s.id as id, s.photo as photo, s.name as name, b.name as brand
              from shoe as s
              INNER join wish as w on w.shoe_id = s.id
              LEFT JOIN brand as b on s.brand_id = b.id
              where w.user_id = {$user_id}";

$result_sell = mysqli_query($con, $query);
$sold_cnt = (int)mysqli_num_rows($result_sell);

?>

<div class="container">
  <div class="mypage-container">
    <div class="user_membership">
      <div class="user_detail">
        <div class="user_thumb">
          <img src="../<?=$user['photo']?>" class="thumb_img">
        </div>
        <div class="user_info">
          <div class="info_box">
            <strong class="name"><?=$user['email']?></strong>
            <p class="email"><?=$user['shoe_size']?></p>
            <a href="./modify.php" class="btn btn outlinegrey small" type="button"> 프로필 수정 </a>
          </div>
        </div>
      </div>
      <div class="membership_detail">
        <a href="#" class="membership_item disabled">
          <strong class="info"> <?=$user['name']?> </strong>
          <p class="title"> 회원 등급 </p>
        </a>
        <a href="#" class="membership_item">
          <strong class="info"> <?=$user['money']?>P </strong>
          <p class="title"> 포인트 </p>
        </a>
      </div>
    </div>
    <div class="content_title border">
      <div class="title">
        <h3>관심 상품</h3>
      </div>
    </div>
    <div class="recent_purchase">
      <ul class="wish_list">
        <li>
          <?php
          for($i = 0; $i < $sold_cnt; $i++) {
            $row = mysqli_fetch_array($result_sell);
            echo "
               <a href='../price/detail.php?id={$row['id']}' class='wish_item'>
                <div class='wish_product'>
                  <div class='product_box'>
                    <div class='product_picture' style='background-color: rgb(235, 240, 245);'>
                      <img src='../{$row['photo']}'>
                    </div>
                  </div>
                  <div class='product_detail'>
                    <div class='brand'>
                      <span class='brand-text'>{$row['brand']} </span>
                    </div>
                    <p class='name'>{$row['name']}</p>
                  </div>
                </div>
              </a>
            ";
          }
          ?>
        </li>
      </ul>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
