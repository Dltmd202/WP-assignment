<!DOCTYPE html>
<html>
  <head>
    <link href="css/header.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
  </head>
  <body>
    <?php
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
                        WHERE os.size = {$size} && os.shoe_id = sh.id && s.is_sold = 0 && DATE_FORMAT(now(), '%Y-%m-%d') <= s.deadline
                        ORDER BY s.price ASC
                        LIMIT 1
                 ) as price
                 FROM shoe as sh
                 LEFT JOIN brand as b on sh.brand_id = b.id
                 LIMIT 4";
      $result = mysqli_query($con, $query);
    ?>

    <?php include 'header.php'; ?>

    <div class="container">
      <div class="home_card_list">
        <div class="banner_slide">
          <button class="slick_arrow_left"></button>
          <div>
          </div>
          <button class="slick_arrow_right"></button>
          <ul class="slick_dots">
            <li>
              <button></button>
            </li>
            <li>
              <button></button>
            </li>
            <li>
              <button></button>
            </li>
          </ul>
        </div>
      </div>
      <div class="shortcut_collection">
        <div class="shortcut_items_wrapper">
          <div class="shortcut_items">
            <div class="shortcut_item">
              <div class="shortcut_item_img">
              </div>
              <p class="shortcut_item_title">NEW!</p>
            </div>
            <div class="shortcut_item">
              <div class="shortcut_item_img">
              </div>
              <p class="shortcut_item_title">NEW!</p>
            </div>
            <div class="shortcut_item">
              <div class="shortcut_item_img">
              </div>
              <p class="shortcut_item_title">NEW!</p>
            </div>
            <div class="shortcut_item">
              <div class="shortcut_item_img">
              </div>
              <p class="shortcut_item_title">NEW!</p>
            </div>
            <div class="shortcut_item">
              <div class="shortcut_item_img">
              </div>
              <p class="shortcut_item_title">NEW!</p>
            </div>
          </div>
        </div>
      </div>
      <div class="home_products">
        <div class="product_title">
          <div class="title">
            Most Popular
          </div>
          <div class="sub_title">
            인기 상품
          </div>
        </div>
        <div class="product_list_wrap">
          <div class="product_list">
            <?php
            for($i = 0; $i < 4; $i++){
              $row = mysqli_fetch_array($result);
              if($row['price'] == null){
                $row['price'] = '-';
              }
              echo "
                <div class='product_item'>
                  <a href='/price/detail.php?id={$row['id']}' class='item_inner'>
                    <div class='thumb_box'>
                      <div class='product'>
                        <img src='{$row['photo']}'>
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

    <?php include 'footer.php'; ?>
  </body>
</html>
