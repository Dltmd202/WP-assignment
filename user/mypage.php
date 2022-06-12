<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/user/user.css"/>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
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
  $row = mysqli_fetch_array($result);
?>

<div class="container">
  <div class="mypage-container">
    <div class="user_membership">
      <div class="user_detail">
        <div class="user_thumb">
          <img src="../<?=$row['photo']?>" class="thumb_img">
        </div>
        <div class="user_info">
          <div class="info_box">
            <strong class="name"><?=$row['email']?></strong>
            <p class="email"><?=$row['shoe_size']?></p>
            <a href="./modify.php" class="btn btn outlinegrey small" type="button"> 프로필 수정 </a>
          </div>
        </div>
      </div>
      <div class="membership_detail">
        <a href="#" class="membership_item disabled">
          <strong class="info"> <?=$row['name']?> </strong>
          <p class="title"> 회원 등급 </p>
        </a>
        <a href="#" class="membership_item">
          <strong class="info"> <?=$row['money']?>P </strong>
          <p class="title"> 포인트 </p>
        </a>
      </div>
    </div>

    <h3>구매 내역</h3>
    <div class="recent_purchase">
      <div class="purchase_list_tab">
        <div class="tab_item total">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">전체</dt>
              <dd class="count">0</dd>
            </dl>
          </a>
        </div>
        <div class="tab_item tab_on">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">입찰 중</dt>
              <dd class="count">0</dd>
            </dl>
          </a>
        </div>
        <div class="tab_item">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">진행 중</dt>
              <dd class="count">0</dd>
              </dl>
          </a>
        </div>
        <div class="tab_item">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">종료</dt>
              <dd class="count">0</dd>
            </dl>
          </a>
        </div>
      </div>
      <div>
        <div class="purchase_list all bid">
          <div class="empty_area">
            <p class="desc">
              거래 내역이 없습니다.
            </p>
          </div>
        </div>
      </div>
    </div>

    <h3>판매 내역</h3>
    <div class="recent_purchase">
      <div class="purchase_list_tab">
        <div class="tab_item total">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">전체</dt>
              <dd class="count">0</dd>
            </dl>
          </a>
        </div>
        <div class="tab_item tab_on">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">입찰 중</dt>
              <dd class="count">0</dd>
            </dl>
          </a>
        </div>
        <div class="tab_item">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">진행 중</dt>
              <dd class="count">0</dd>
            </dl>
          </a>
        </div>
        <div class="tab_item">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">종료</dt>
              <dd class="count">0</dd>
            </dl>
          </a>
        </div>
      </div>
      <div>
        <div class="purchase_list all bid">
          <div class="empty_area">
            <p class="desc">
              거래 내역이 없습니다.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
