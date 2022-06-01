<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <link href="/css/price.css" rel="stylesheet">
  <link href="/css/order.css" rel="stylesheet">
  <title>ShoeKream</title>
  <link rel="shortcut icon" href="../img/fav.png">
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
            <a href="#" class="brand">
              <?= $row['brand']?>
            </a>
            <p class="title">
              <?= $row['name']?>
            </p>
            <p class="desc">
              <!--- TODO 상품에 desc column 추가하기 -->
              조던 1 로우 골드 스타피쉬
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
                    260
                  </span>
                </a>
              </div>
            </div>
            <div class="form_wrap">
              <form autocomplete="off" method="post" name="sell">
                <div class="direct"
                  <?php
                  if(!isset($row['direct_selling_price'])){
                    echo "style='display: none'";
                  }
                  ?>
                >
                  <div class="direct_box wrap_partition">
                    <div class="direct_label">
                      <span id="min_price"><?=$row['direct_selling_price']?></span>
                      원에 즉시 판매하기
                    </div>
                    <div>
                        <input type="checkbox" name="immediate"
                               value="immediate" id="immediate_check" onchange="isImmediatelyPurchaseChecked(this)">
                      <label for="immediate_check"></label>
                    </div>
                  </div>
                </div>
                <div class="order_bid">
                  <div class="price">
                    <div>
                      판매 희망가
                    </div>
                    <div class="price_input">
                      <input type="number" name="price" id="price"
                             inputmode="numeric" pattern="[0-9]*"
                             placeholder="희망가 입력 "
                             onchange="priceChanged(<?=$row['direct_selling_price']?>)">원
                    </div>
                    <label for="price"></label>
                  </div>
                  <div class="price">
                    <div>
                      입찰 마감기한
                    </div>
                    <div>
                      <input type="number" name="period" id="period"
                             inputmode="numeric" pattern="[0-9]*"
                             placeholder="입찰기한 "
                             onchange="priceChanged(<?=$row['direct_selling_price']?>)"
                      >일
                      <label for="period"></label>
                    </div>
                  </div>

                  <button type="button" class="submit-btn"
                          onclick="sellCheck('?id=<?=$id?>&size=<?=$size?>&order_id=<?=$row['order_id']?>')">
                    판매 입찰하기
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include '../footer.php'; ?>
<script src="../js/sell.js"></script>
</body>
</html>
