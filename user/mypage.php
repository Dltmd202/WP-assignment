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
  <link href="/css/user/wish.css" rel="stylesheet">
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
  $size = $_SESSION['size'];
  $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database)
or die("Can't access DB");

  $query = "SELECT * from user as u 
                LEFT JOIN Authority as au on u.authority_id = au.id
                where u.id = {$user_id}";
  $result = mysqli_query($con, $query);
  $user = mysqli_fetch_array($result);

  $query = "select sh.id as id, sh.photo as photo, sh.name as name, br.name as brand, 
              date_format(t.dtime, '%Y-%m-%d %H:%i') as dt
              from bid as b
              INNER JOIN trade as t on t.bid_id = b.id
              inner join order_shoe as os on b.order_shoe_id = os.id
              inner join shoe as sh on os.shoe_id = sh.id
              inner join brand as br on sh.brand_id = br.id
              where b.user_id = {$user_id} && os.size = {$size}";

  $result_bought = mysqli_query($con, $query);
  $bought_cnt = mysqli_num_rows($result_bought);

  $query = "select sh.id as id, sh.photo as photo, sh.name as name, br.name as brand,
              date_format(t.dtime, '%Y-%m-%d %H:%i') as dt
              from Sell as s
              INNER join trade as t on t.sell_id = s.id
              inner join order_shoe as os on s.order_shoe_id = os.id
              inner join shoe as sh on os.shoe_id = sh.id
              inner join brand as br on sh.brand_id = br.id
              where s.user_id = {$user_id} && os.size = {$size}";

  $result_sell = mysqli_query($con, $query);
  $sold_cnt = (int)mysqli_num_rows($result_sell);

  $query = "select count(*)
                from bid as b
                where b.user_id = {$user_id} && b.is_success = 0 && 
                DATE_FORMAT(now(), '%Y-%m-%d') <= b.deadline";

  $bidding_cnt_result = mysqli_query($con, $query);
  $bidding_cnt = (int)mysqli_fetch_assoc($bidding_cnt_result)['count(*)'];

  $query = "select count(*)
                  from bid as b
                  where b.user_id = {$user_id} && b.is_success = 0 && 
                  DATE_FORMAT(now(), '%Y-%m-%d') > b.deadline";

  $bidding_cnt_invalid_result = mysqli_query($con, $query);
  $bidding_cnt_invalid = (int)mysqli_fetch_assoc($bidding_cnt_invalid_result)['count(*)'];


  $query = "select count(*)
                  from sell as s
                  where s.user_id = {$user_id} && s.is_sold = 0 &&
                  DATE_FORMAT(now(), '%Y-%m-%d') <= s.deadline";

  $selling_cnt_result = mysqli_query($con, $query);
  $selling_cnt = (int)mysqli_fetch_assoc($selling_cnt_result)['count(*)'];

  $query = "select count(*)
                  from sell as s
                  where s.user_id = {$user_id} && s.is_sold = 0
                  && DATE_FORMAT(now(), '%Y-%m-%d') > s.deadline;";

  $selling_cnt_invalid_result = mysqli_query($con, $query);
  $selling_cnt_invalid = (int)mysqli_fetch_assoc($selling_cnt_invalid_result)['count(*)'];

  $bought_total_cnt = $bought_cnt + $bidding_cnt_invalid + $bidding_cnt;
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

    <h3>구매 내역</h3>
    <div class="recent_purchase">
      <div class="purchase_list_tab">
        <div class="tab_item total purchased">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">전체</dt>
              <dd class="count">
                <?php
                echo $bought_cnt + $bidding_cnt_invalid + $bidding_cnt;
                ?>
              </dd>
            </dl>
          </a>
        </div>
        <div class="tab_item tab_on">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">입찰 중</dt>
              <dd class="count">
                <?= $bidding_cnt ?>
              </dd>
            </dl>
          </a>
        </div>
        <div class="tab_item">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">구매 완료</dt>
              <dd class="count"><?= $bought_cnt ?></dd>
              </dl>
          </a>
        </div>
        <div class="tab_item">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">종료</dt>
              <dd class="count">
                <?= $bidding_cnt_invalid ?>
              </dd>
            </dl>
          </a>
        </div>
      </div>
      <div>
        <div class="purchase_list all bid">
          <div class="recent_purchase">
            <ul class="wish_list">
              <?php
              if($bought_cnt > 0){
                for($j = 0; $j < $bought_cnt; $j++){
                  $bought = mysqli_fetch_array($result_bought);
                  echo "
                     <a href='../price/detail.php?id={$bought['id']}' class='wish_item'>
                      <div class='wish_product'>
                        <div class='product_box'>
                          <div class='product_picture' style='background-color: rgb(235, 240, 245);'>
                            <img src='../{$bought['photo']}'>
                          </div>
                        </div>
                        <div class='product_detail'>
                          <div class='brand'>
                            <span class='brand-text'>{$bought['brand']} </span>
                          </div>
                          <p class='name'>{$bought['name']}</p>
                          <p class='name'>{$bought['dt']}</p>
                        </div>
                      </div>
                    </a>
                  ";
                }
              } else {
                echo "
                <div class='empty_area'>
                  <p class='desc'>
                    거래 내역이 없습니다.
                  </p>
                </div>
                ";
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <h3>판매 내역</h3>
    <div class="recent_purchase">
      <div class="purchase_list_tab">
        <div class="tab_item total sold">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">전체</dt>
              <dd class="count">
                <?php
                  echo $sold_cnt + $selling_cnt + $selling_cnt_invalid;
                ?>
              </dd>
            </dl>
          </a>
        </div>
        <div class="tab_item tab_on">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">입찰 중</dt>
              <dd class="count"><?= $selling_cnt ?></dd>
            </dl>
          </a>
        </div>
        <div class="tab_item">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">판매 완료</dt>
              <dd class="count"><?= $sold_cnt ?></dd>
            </dl>
          </a>
        </div>
        <div class="tab_item">
          <a href="#" class="tab_link">
            <dl class="tab_box">
              <dt class="title">종료</dt>
              <dd class="count"><?= $selling_cnt_invalid?></dd>
            </dl>
          </a>
        </div>
      </div>
      <div>
        <div class="purchase_list all bid">
          <div class="recent_purchase">
            <ul class="wish_list">
              <?php
                if($sold_cnt > 0){
                  for($i = 0; $i < $sold_cnt; $i++){
                    $sold = mysqli_fetch_array($result_sell);
                    echo "
                     <a href='../price/detail.php?id={$sold['id']}' class='wish_item'>
                      <div class='wish_product'>
                        <div class='product_box'>
                          <div class='product_picture' style='background-color: rgb(235, 240, 245);'>
                            <img src='../{$sold['photo']}'>
                          </div>
                        </div>
                        <div class='product_detail'>
                          <div class='brand'>
                            <span class='brand-text'>{$sold['brand']} </span>
                          </div>
                          <p class='name'>{$sold['name']}</p>
                          <p class='name'>{$sold['dt']}</p>
                        </div>
                      </div>
                    </a>
                  ";
                  }
                } else {
                  echo "
                    <div class='empty_area'>
                      <p class='desc'>
                        거래 내역이 없습니다.
                      </p>
                    </div>
                  ";
                }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
