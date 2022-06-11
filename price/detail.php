<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/price.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <title>ShoeKream</title>
  <link rel="shortcut icon" href="../img/fav.png">
</head>
<body>

<?php include '../header.php'; ?>

<?php
  require("../conf/conf_db.php");
  $id = $_GET['id'];

  if(isset($_SESSION['size'])){
    $size = $_SESSION['size'];
  } else {
    $size = 260;
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
                   ) as direct_selling_price 
                   FROM shoe as sh
                   LEFT JOIN brand as b on sh.brand_id = b.id
                   WHERE sh.id = {$id}";
  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_array($result);
  if($row['direct_purcharse_price'] == null){
    $row['direct_purcharse_price'] = '-';
  }
?>

<div class="container">
  <div class="content">
    <div class="col">
      <div class="detail_banner">
        <div class="item_picture">
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
                <div class="amout">
                  <!--- TODO 최근거래금액 로직 넣기 -->
                  <span class="num">230,000</span>
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
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
