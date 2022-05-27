<header>
  <link href="/css/header.css" rel="stylesheet">
  <div class="header_top">
    <div class="top_inner">
      <ul class="top_list">
        <li class="top_item">
          <a href="/#" class="top_link"> 고객센터 </a>
        </li>
        <li class="top_item">
          <a href="/user/mypage.php" class="top_link"> 관심상품 </a>
        </li>
        <li class="top_item">
          <a href="/user/mypage.php" class="top_link"> 마이페이지 </a>
        </li>
        <?php
          if(!isset($_SESSION['email'])){
            echo "
             <li class='top_item'>
                <a href='/user/login.php' class='top_link'> 로그인 </a>
             </li>
             <li class='top_item'>
                <a href='/user/create.php' class='top_link'> 회원가입 </a>
            </li>
            ";
          } else {
            echo "
              <li class='top_item'>
                <a href='/user/logout.php' class='top_link'> 로그아웃 </a>
              </li>
            ";
          }
        ?>
      </ul>
    </div>
  </div>
  <div class="main_inner main_inner_nav">
    <h1>
      <a href="/index.php" class="logo">ShoeKream</a>
    </h1>
    <nav class="gnb">
      <ul class="gnb_list">
        <li class="gnb_item">
          <a href="/price/list.php" class="gnb_link"> STYLE </a>
        </li>
        <li class="gnb_item">
          <a href="/price/list.php" class="gnb_link"> SHOP </a>
        </li>
        <li class="gnb_item">
          <a href="/price/list.php" class="gnb_link"> ABOUT </a>
        </li>
      </ul>
    </nav>
  </div>
</header>
