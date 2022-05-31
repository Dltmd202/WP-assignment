<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container">

  <h1>회원가입</h1>
  <form action="./create_action.php" method="post" accept-charset="utf-8">
    <label>
      이메일 주소
      <input type="email" name="email"/>
    </label>
    <label>
      비밀번호
      <input type="password" name="password"/>
    </label>
    <label>
      신발 사이즈
      <input type="number" name="size">
    </label>
    <input type="submit" value="회원가입">
  </form>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
