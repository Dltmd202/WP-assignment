<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <link href="/css/user/create.css" rel="stylesheet">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container_content">

  <h1>회원가입</h1>
  <form method="post" accept-charset="utf-8" name="signup">
    <div class = "login_area">
      <h2>이메일주소</h2>
      <label class = "idform">
        <input type="email" name="email" placeholder="E-mail" id="email">
      </label>
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
      <div>
        <button type="button" class = "create_btn" onclick="signupCheck()">회원가입</button>
      </div>
    </div>

  </form>
</div>

<?php include '../footer.php'; ?>
<script>
    function signupCheck(){
        var form = document.signup;
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
        if(!form.size.value){
            alert("사이즈를 입력해 주세요");
            form.password.focus();
            return;
        }
        form.action = "./create_action.php";
        form.submit();
    }
</script>
</body>
</html>
