<?php
session_start();

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: dangnhap.php");
  exit();
}

if (!isset($_SESSION['account'])) {
  header("Location: error.php");
  exit();
}

$account = $_SESSION['account'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<title>Trang Chủ</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style_home.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel='shortcut icon' href='/meo_den.ico'/>
<style>
  body,h1,h2,h3,h4,h5 {font-family: "Poppins", sans-serif}
  body {font-size:16px;}
  .w3-half img{margin-bottom:-6px;margin-top:16px;opacity:0.8;cursor:pointer}
  .w3-half img:hover{opacity:1}

footer{
  margin: 0;
  padding: 0;
}
</style>
</head>
<body>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-red w3-collapse w3-top w3-large w3-padding" style="z-index:3;width:250px;font-weight:bold;" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-topleft" style="width:100%;font-size:22px">Close Menu</a>
  <div class="w3-container">
    <h3 class="w3-padding-64"><b>Đồ Án Nhóm 1</b></h3>
  </div>
  <div class="w3-bar-block">
    <a href="#" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Trang chủ</a>
  <!--  <a href="#showcase" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Linh Kiện</a>  -->
    <a href="#designers" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Các thông tin cần thiết</a> 
    <a href="#contact" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Liên hệ</a>
    <div id="userDiv" style="position: fixed; top: 0; right: 0; padding: 10px; background-color: #f44336;">
    <?php if(!empty($account)): ?>
      Hi, <?php echo $account; ?> | <a href="?logout=true">Logout</a>
    <?php endif; ?>
  </div>
  </div>
</nav>

<!-- Menu trên màn hình nhỏ -->
<header class="w3-container w3-top w3-hide-large w3-red w3-xlarge w3-padding">
  <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onclick="w3_open()">☰</a>
  <span></span>
</header>

<!-- Khi màn hình nhỏ thì sidebar theo kích thước -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:250px;margin-right:10px">

  <!-- Header -->
  <div class="w3-container" style="margin-top:80px" id="showcase">
    <h1 class="w3-jumbo" style="text-align: center;"><b>Trang chủ của Đồ án Nhóm 1</b></h1>
    <h1 class="w3-xxxlarge w3-text-red"><b>Nội dung chính</b></h1>
    <hr style="width:100%;max-width:350px;border:5px solid red" class="w3-round">
  </div>
  
  <div id="map" name="map" class="w3-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d944.9533011975307!2d105.70635625323412!3d18.672376435634!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139cdc689cc3d1d%3A0x4c39c1211911a5db!2sVinh%20University%20of%20Technology%20Education!5e0!3m2!1sen!2s!4v1682058208255!5m2!1sen!2s" width="100%" height="400" style="border:solid 5px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <a href="http://ktmtk15.rf.gd/webdata.php?i=1" style ="  display: flex; justify-content: center;align-items: center;"><b>Bấm vào đây để xem thông tin chi tiết</b></a>
  </div>

  <div class="w3-container" id="designers" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Các thông tin về thành viên Nhóm</b></h1>
    <hr style="width:100%;max-width:830px;border:5px solid red" class="w3-round">
    <h3>Các thông tin về thành viên</h3>
    <!-- Các thành viên-->
    <div class="w3-row-padding w3-grayscale">
      <div class="w3-col m4 w3-margin-bottom">
        <div class="w3-light-grey">
          <div class="w3-container">
            <h3>Vi Minh Tú</h3>
            <p class="w3-opacity">Nhóm trưởng</p>
          </div>
        </div>
      </div>
      <div class="w3-col m4 w3-margin-bottom">
        <div class="w3-light-grey">
          <div class="w3-container">
            <h3>Trần Quang Linh</h3>
            <p class="w3-opacity">Thành Viên Nhóm</p>
          </div>
        </div>
      </div>
      <div class="w3-col m4 w3-margin-bottom">
        <div class="w3-light-grey">
          <div class="w3-container">
            <h3>Nguyễn Anh Đạt</h3>
            <p class="w3-opacity">Thành Viên Nhóm</p>
          </div>
        </div>
      </div>
      <div class="w3-col m4 w3-margin-bottom">
        <div class="w3-light-grey">
          <div class="w3-container">
            <h3>Hoàng Mạnh Đức</h3>
            <p class="w3-opacity">Thành Viên Nhóm</p>
          </div>
        </div>
      </div>
      <div class="w3-col m4 w3-margin-bottom">
        <div class="w3-light-grey">
          <div class="w3-container">
            <h3>Võ Viết Chương</h3>
            <p class="w3-opacity">Thành Viên Nhóm</p>
          </div>
        </div>
      </div>
      <div class="w3-col m4 w3-margin-bottom">
        <div class="w3-light-grey">
          <div class="w3-container">
            <h3>Nguyễn Xuân Hiếu</h3>
            <p class="w3-opacity">Thành Viên Nhóm</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="w3-container" id="contact" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Liên Hệ</b></h1>
    <hr style="width:100%;max-width:150px;border:5px solid red" class="w3-round">
    <p>Bạn muốn liên lạc nhanh với chúng tôi thì có thể thông qua:</p>
    <form action="guiykien.php" method="post">
      <div class="w3-section">
        <label>Tên</label>
        <input class="w3-input w3-border" type="text" name="Name" required>
      </div>
      <div class="w3-section">
        <label>Email</label>
        <input class="w3-input w3-border" type="email" name="Email" required>
      </div>
      <div class="w3-section">
        <label>Lời nhắn</label>
        <input class="w3-input w3-border" type="text" name="Message" required>
      </div>
      <button type="submit" name="submit" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Gửi phản hồi</button>
    </form>  
  </div>

</div>


<footer style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
  <p id="footer-1">Mọi thông tin và ý tưởng bản quyền thuộc Nhóm 1</p>
 
</footer>


<script>
  function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
  }
  
  function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
  }
  
  function onClick(element) {
    document.getElementById("img01").src = element.src;
    document.getElementById("modal01").style.display = "block";
    var captionText = document.getElementById("caption");
    captionText.innerHTML = element.alt;
  }

  var username = "<?php echo $hovaten; ?>"; // Retrieve the username from PHP variable
document.getElementById("username").innerHTML = "Hi, " + username;
document.getElementById("userDiv").style.display = "block";

</script>

</body>
</html>
