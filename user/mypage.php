<!DOCTYPE html>
<html>
<head>
  <link href="/css/header.css" rel="stylesheet">
  <link href="/css/common.css" rel="stylesheet">
  <link href="/css/index.css" rel="stylesheet">
  <link href="/css/footer.css" rel="stylesheet">
  <title>ShoeKream</title>
  <link rel="shortcut icon" href="../img/fav.png">
</head>
<body>

<?php include '../header.php'; ?>
<?php

  if(!isset($_SESSION['user_id'])){
    echo "
    <script>
      alert('로그인 해주세요');
      location.href='..';
      </script>
    ";
  }
  $user_id = $_SESSION['user_id'];
  $con = mysqli_connect("localhost", "root", "1234", "wp")
  or die("Can't access DB");

  $query = "SELECT * from user as u where u.id = {$user_id}";
  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_array($result);
?>

<div class="container">
  <div>
    이메일 : <?=$row['email']?>
  </div>
  <div>
    신발 사이즈 : <?=$row['shoe_size']?>
  </div>
  <div>
    돈 : <?=$row['money']?>
  </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
