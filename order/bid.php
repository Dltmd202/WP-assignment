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
  require("../conf/conf_db.php");
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

  $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database)
  or die("Can't access DB");

  $query = "SELECT sh.id as id, sh.photo as photo, sh.name as name, b.name as brand, os.id as order_id, sh.desc as shdesc,
                   (
                      SELECT s.price
                          FROM sell as s
                          LEFT JOIN order_shoe as os on s.order_shoe_id = os.id
                          WHERE os.size = {$size} && os.shoe_id = {$id} && s.is_sold = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= s.deadline
                          ORDER BY s.price ASC
                          LIMIT 1
                   ) as direct_purcharse_price
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
                    260
                  </span>
                </a>
              </div>
            </div>
            <div class="form_wrap">
              <form autocomplete="off" method="post" name="bid">
                <div class="direct"
                  <?php
                  if(!isset($row['direct_purcharse_price'])){
                    echo "style='display: none'";
                  }
                  ?>
                >
                  <div class="direct_box wrap_partition">
                    <div class="direct_label">
                      <span id="min_price"><?=$row['direct_purcharse_price']?></span>
                      원에 즉시 구매하기
                    </div>
                    <div>
                      <input type="checkbox" name="immediate" class="direct_checkbox"
                             value="immediate" id="immediate_check" onchange="isImmediatelyPurchaseChecked(this)">
                      <label for="immediate_check"></label>
                    </div>
                  </div>
                </div>
                <div class="order_bid">
                  <div class="price">
                    <div>
                      구매 희망가
                    </div>
                    <div class="price_input">
                      <input type="number" name="price" id="price"
                             inputmode="numeric" pattern="[0-9]*"
                             placeholder="희망가 입력 "
                             onchange="priceChanged(<?=$row['direct_purcharse_price']?>)">원
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
                             onchange="priceChanged(<?=$row['direct_purcharse_price']?>)"
                      >일
                      <label for="period"></label>
                    </div>
                  </div>
                  <button type="submit" value="구매 입찰하기" class="submit-btn"
                          onclick="bidCheck('?id=<?=$id?>&size=<?=$size?>&order_id=<?=$row['order_id']?>')">
                    구매 입찰하기
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
<script src="../js/bid.js"></script>
</body>
</html>
