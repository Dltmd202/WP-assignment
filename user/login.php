<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container">
  <h1>ShoeKream</h1>
  <form action="./login_action.php" method="post">
    <label>
      이메일 주소
      <input type="email" name="email">
    </label>
    <label>
      비밀번호
      <input type="password" name="password" >
    </label>
    <input type="submit" value="submit">
  </form>

</div>

<?php include '../footer.php'; ?>
</body>
</html>
