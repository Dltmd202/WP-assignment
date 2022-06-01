<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <link href="/css/user/login.css" rel="stylesheet">
  <title>ShoeKream</title>
  <link rel="shortcut icon" href="../img/fav.png">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container_content">
  <h1>ShoeKream</h1>
  <form method="post" name="login">
    <div class = "login_area">
      <h2>이메일주소</h2>
      <label class = "idform">
        <input type="email" name="email" placeholder="E-mail" id="email">
      </label>
      <h2>비밀번호</h2>
      <label class = "passwordform">
        <input type="password" name="password" placeholder="Password" id="password">
      </label>
      <div>
        <button class = "login_btn" onclick="loginCheck()">login</button>
      </div>
    </div>

  </form>

</div>

<?php include '../footer.php'; ?>
<script>
  function loginCheck(){
      var form = document.login;
      if(!form.email.value){
          alert("아이디를 입력해 주세요");
          form.email.focus();
          return;
      }
      if(!form.password.value){
          alert("비밀번호를 입력해 주세요");
          form.password.focus();
          return;
      }
      form.action = "./login_action.php";
      form.submit();
  }
</script>
</body>
</html>
