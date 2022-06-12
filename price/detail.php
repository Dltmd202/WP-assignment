<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/price.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <title>ShoeKream</title>
  <link rel="shortcut icon" href="../img/fav.png">
  <link rel="stylesheet" href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css'/>
</head>
<body>

<?php include '../header.php'; ?>

<?php
  require("../conf/conf_db.php");
  $id = $_GET['id'];

  if(isset($_SESSION['size'])){
    $size = $_SESSION['size'];
    $user_id = $_SESSION['user_id'];
  } else {
    $size = 260;
    $user_id = 0;
  }
  $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database)
  or die("Can't access DB");

  $query = "SELECT sh.id as id, sh.photo as photo, sh.name as name, b.name as brand, sh.desc as shdesc,
                   (
                      SELECT s.price
                          FROM sell as s
                          LEFT JOIN order_shoe as os on s.order_shoe_id = os.id
                          WHERE os.size = {$size} && os.shoe_id = {$id} && s.is_sold = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= s.deadline
                          ORDER BY s.price ASC
                          LIMIT 1
                   ) as direct_purcharse_price,
                   (
                      SELECT b.price
                          FROM bid as b
                          LEFT JOIN order_shoe as os on b.order_shoe_id = os.id
                          WHERE os.size = {$size} && os.shoe_id = {$id} && b.is_success = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= b.deadline 
                          ORDER BY b.price DESC 
                          LIMIT 1
                   ) as direct_selling_price,
                   (
                      select b.price
                        from Trade as t
                        left join bid as b on b.id = t.bid_id
                        left join order_shoe as os on b.order_shoe_id = os.id
                        where os.size = {$size} && os.shoe_id = {$id}
                        order by t.dtime desc
                        limit 1
                   ) as previous_trade_price 
                   FROM shoe as sh
                   LEFT JOIN brand as b on sh.brand_id = b.id
                   WHERE sh.id = {$id}";
  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_array($result);
  if($row['direct_purcharse_price'] == null){
    $row['direct_purcharse_price'] = '-';
  }
  if($row['direct_selling_price'] == null){
    $row['direct_selling_price'] = '-';
  }
  if($row['previous_trade_price'] == null){
    $row['previous_trade_price'] = '-';
  }
  $shoe_id = $row['id'];
  $query = "select count(*)
              from Wish
              where shoe_id = {$row['id']} && user_id = {$user_id}";
  $wish_user_result = mysqli_query($con, $query);
  $user_wish_cnt =  mysqli_fetch_array($wish_user_result)['count(*)'];

  $query = "select count(*)
                from Wish
                where shoe_id = {$row['id']}";
  $wish_result = mysqli_query($con, $query);
  $wish_cnt =  mysqli_fetch_array($wish_result)['count(*)'];
?>

<div class="container">
  <div class="content">
    <div class="col">
      <div class="detail_banner">
        <div class="item_picture" style="background: <?= colorDistributer($row['id'] % 4) ?>">
          <img src="../<?= $row['photo']?>">
        </div>
      </div>
    </div>
    <div class="col">
      <div class="right_col">
        <div class="column-top">
          <div class="main-title">
            <a href="#" class="brand">
              <?= $row['brand']?>
            </a>
            <p class="title">
              <?= $row['name']?>
            </p>
            <p class="desc">
              <?= $row['shdesc']?>
            </p>
          </div>
          <div class="product_figure_wrap">
            <div class="detail_size wrap_partition">
              <div class="title">
                <span class="title_txt">
                  사이즈
                </span>
              </div>
              <div class="size">
                <a href="" class="btn-size">
                  <span class="btn_text">
                    <?php
                      $id = $_GET['id'];

                      if(isset($_SESSION['size'])){
                        echo $_SESSION['size'];
                      } else {
                        echo "로그인하세요";
                      }
                      ?>
                  </span>
                </a>
              </div>
            </div>
            <div class="detail_price">
              <div class="title">
                <span class="title_txt">
                  최근 거래가
                </span>
              </div>
              <div class="price">
                <div class="amout"
                  <span class="num"><?=$row['previous_trade_price']?></span>
                  <span class="won">원</span>
                </div>
              </div>
            </div>
          </div>
          <div class="btn-wrap">
            <div class="division_btn_box">
              <a href="../order/bid.php?id=<?=$id?>&size=<?=$size?>" class="btn">
                <strong class="title">구매</strong>
                <div class="price">
                  <span class="amount">
                    <em class="num"><?= $row['direct_purcharse_price']?></em>
                    <span class="won">원</span>
                  </span>
                  <span class="desc">즉시 구매가</span>
                </div>
              </a>
              <a href="../order/sell.php?id=<?=$id?>&size=<?=$size?>" class="btn">
                <strong class="title">판매</strong>
                <div class="price">
                  <span class="amount">
                    <em class="num"><?=$row['direct_selling_price']?></em>
                    <span class="won">원</span>
                  </span>
                  <span class="desc">즉시 판매가</span>
                </div>
              </a>
            </div>
            <a href="./wish_action.php?id=<?=$id?>" class="btn full large btn_wish">
              <?php
              if($user_wish_cnt == 0){
                echo "
                   <i class='fa-regular fa-bookmark'></i>
                ";
              }else {
                echo "
                     <i class='fa-solid fa-bookmark'></i>
                     ";
              }

                ?>
              <span class="btn_text"> 관심상품</span>
              <span data-v-2d0ab5c1="" class="wish_count_num">
                <?=$wish_cnt?>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://kit.fontawesome.com/d4682b94f6.js" crossorigin="anonymous"></script>
<?php include '../footer.php'; ?>
</body>
</html>
