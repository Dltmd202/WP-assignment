<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/price.css" rel="stylesheet">
</head>
<body>

<?php include '../header.php'; ?>

<?php
  $id = $_GET['id'];

  if(isset($_SESSION['size'])){
    $size = $_SESSION['size'];
  } else {
    $size = 260;
  }
  $con = mysqli_connect("localhost", "root", "1234", "wp")
  or die("Can't access DB");

  $query = "SELECT sh.id as id, sh.photo as photo, sh.name as name, b.name as brand,
                   (
                      SELECT s.price
                          FROM sell as s
                          LEFT JOIN order_shoe as os on s.order_shoe_id = os.id
                          WHERE os.size = {$size} && os.id = {$id} && s.is_sold = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= s.deadline
                          ORDER BY s.price ASC
                          LIMIT 1
                   ) as direct_purcharse_price
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
      <div class="left_col">
        <div class="detail_banner">
          <div class="item_picture">
            <img src="../<?= $row['photo']?>">
          </div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="right_col">
        <div class="column-top">
          <div class="main-title">
            <a href="#">
              <?= $row['brand']?>
            </a>
            <p>
              <?= $row['name']?>
            </p>
            <p>
              조던 1 로우 골드 스타피쉬
            </p>
          </div>
          <div class="product_figure_wrap">
            <div class="detail_size">
              <div class="title">
                <span class="title_txt">
                  사이즈
                </span>
              </div>
              <div class="size">
                <a href="" class="btn-size">
                  <span class="btn_text">
                    모든 사이즈
                  </span>
                  <img src="../img/dropdownbtn.svg"/>
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
                  <span class="num" data-v-5943a237="">230,000</span>
                  <span class="won" data-v-5943a237="">원</span>
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
              <a href="#" class="btn">
                <strong class="title">판매</strong>
                <div class="price">
                  <span class="amount">
                    <em class="num">245,000</em>
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
