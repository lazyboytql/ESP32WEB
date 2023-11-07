<?php 
session_start();

if ($valid_credentials) {

    $_SESSION['account'] = $account;
    header("Location: home.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel='shortcut icon' href='meo_den.ico'/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Đăng nhập vào trang web đồ án của nhóm 1</title>
    <style>

      .ghost {
        background-color: transparent;
        border: 2px solid #ffffff;
        color: #ffffff;
        padding: 12px 24px;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
      }

      .ghost:hover {
        background-color: #ffffff;
        color: #000000;
        border-color: #ffffff;
      }

      .ghost:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
      }


      .ghost#signIn:hover,
      .ghost#signUp:hover {
        background-color: #000000;
        color: #ffffff;
        border-color: #000000;
      }
      
        button[name="submit"] {
        background-color: orange;
        border: none;
        color: #ffffff;
        padding: 12px 24px;
        transition: background-color 0.3s, color 0.3s;
      }

      button[name="submit"]:hover {
        background-color: darkorange;
      }

      button[name="submit"]:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.5);
      }
    </style>
</head>
<body>
    <div class="container" id="container">
      <div class="form-container sign-up-container"> 
        <form action="formdangki.php" method="post">
          <h1>ĐĂNG KÝ</h1>
          <input type="text" placeholder="Họ và Tên" name="hovaten" required  min="4"  max="25" value="<?php if(isset($_POST["hovaten"])) echo $_POST["hovaten"]; else echo "";?>"/>
          <input type="email" placeholder="Email" name="email" required min="0" max  value="<?php if(isset($_POST["email"])) echo $_POST["email"]; else echo "";?>"/>
          <input type="text" placeholder="Tên tài khoản" name="account" required value="<?php if(isset($_POST["account"])) echo $_POST["account"]; else echo "";?>" />
          <input type="password" placeholder="Password" name="pass" id="password-input" required  value="<?php if(isset($_POST["pass"])) echo $_POST["pass"]; else echo "";?>"/>
          <input type="password" placeholder="re-enter password" name="repass" id="repassword-input" required  value="<?php if(isset($_POST["repass"])) echo $_POST["repass"]; else echo "";?>"/>
          <p class="show-password" id="show-password-toggle">Hiện mật khẩu </p required>          
          <button type="submit" name="submit" value="Đăng ký ngay" >Đăng Ký </button required>
      
        </form>
      </div>
      <div class="form-container sign-in-container">
        <form action="formdangnhap.php" method="post">
          <h1>ĐĂNG NHẬP</h1>
          <input type="text" placeholder="Tên tài khoản" name="account" required value="<?php if(isset($_POST["account"])) echo $_POST["account"]; else echo "";?>" />
          <input type="password" placeholder="Password" name="pass" required value="<?php if(isset($_POST["pass"])) echo $_POST["pass"]; else echo "";?>" />
          <button type="submit" name="submit" style="margin-top :30px">ĐĂNG NHẬP</button>
          
        </form>
      
      </div>
      <div class="overlay-container">
        <div class="overlay">
          <div class="overlay-panel overlay-left">
            <h2>Nhóm 1 nhắc nhở bạn</h2>
            <p>Nếu bạn đã có tài khoản có sẵn thì có thể đăng nhập theo nút bên dưới</p>
            <button class="ghost" id="signIn">Đăng Nhập</button>
          </div>
          <div class="overlay-panel overlay-right">
            <h2>Nhóm 1 Chào bạn!</h2>
            <p>Nếu không có tài khoản thì mời bạn đăng ký tài khoản để có thể sử dụng dịch vụ của chúng tôi  </p>
            <button class="ghost" id="signUp">Đăng Ký</button>
          </div>
        </div>
      </div>
    </div>
    <script>
      const signUpButton = document.getElementById('signUp');
      const signInButton = document.getElementById('signIn');
      const container = document.getElementById('container');

      signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
      });

      signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
      });
      const passwordInput = document.getElementById('password-input');
      const repasswordInput = document.getElementById('repassword-input');
      const showPasswordToggle = document.getElementById('show-password-toggle');
      const submitButton = document.querySelector("button[type='submit']");
      const passwordMatchMessage = document.getElementById('password-match-message');


      showPasswordToggle.addEventListener('click', () => {
       if (passwordInput.type === 'password') {
         passwordInput.type = 'text';
         repasswordInput.type = 'text';
         showPasswordToggle.textContent = 'Ẩn mật khẩu';
       } else {
         passwordInput.type = 'password';
         repasswordInput.type = 'password';
         showPasswordToggle.textContent = 'Hiện mật khẩu';  
       }
      });
      repasswordInput.addEventListener('input', () => {
         if (passwordInput.value === repasswordInput.value) {
       passwordMatchMessage.textContent = 'Password khớp';
        } else {
       passwordMatchMessage.textContent = 'Passwords không khớp';
        }
      });
 </script>

</body>
<footer>
  <p id="footer-1">Mọi thông tin và ý tưởng bản quyền thuộc Nhóm 1 </p></br>
  <p>Mọi thắc mắc xin liên hệ thông qua địa chỉ:
    <dl>
       <dd>Nhóm 1</dd>
      <dd> <a href="https://www.facebook.com/tqlboylazyboytql">Facebook</a></dd>
      <dd>0522533xxx</dd>
   </dl>
   
  </p>
 
</footer>
</html>
