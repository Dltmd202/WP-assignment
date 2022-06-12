<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <link href="/css/user/create.css" rel="stylesheet">
  <title>ShoeKream</title>
  <link rel="shortcut icon" href="../img/fav.png">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container_content">
  <h1>회원정보 수정</h1>
  <form method="post" accept-charset="utf-8" name="signup">
    <div class = "login_area">
      <h2>비밀번호</h2>
      <label class = "passwordform">
        <input type="password" name="password" placeholder="Password" id="password">
      </label>
      <h3>신발 사이즈</h3>
      <label class = "selec_size">
        <input type="number" name="size" id="size"
               inputmode="numeric" pattern="[0-9]*"
               placeholder="사이즈를 입력하세요">
      </label>
      <h3>프로필 사진</h3>
      <label class = "selec_size">
        <input type="file" name="photo" id="photo" accept="image/*"
               inputmode="numeric" pattern="[0-9]*"
               placeholder="사이즈를 입력하세요">
      </label>
      <div>
        <button type="button" class = "create_btn" onclick="signupCheck()">회원정보 수정</button>
      </div>
    </div>

  </form>
</div>

<?php include '../footer.php'; ?>
<script>
    function modifyCheck(){
        alert("개발 중 입니다.");
        href.location = "../"
    }
</script>
</body>
</html>
