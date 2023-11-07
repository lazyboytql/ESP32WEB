<?php

include 'config.php';

if (isset($_POST['submit']) && !empty($_POST['hovaten']) && !empty($_POST['email']) && !empty($_POST['account']) && !empty($_POST['pass']) && !empty($_POST['repass'])) {
    $hovaten = $_POST["hovaten"];
    $email = $_POST["email"];
    $account = $_POST["account"];
    $pass = $_POST["pass"];
    $repass = $_POST["repass"];

    // Validate input data
    if ($pass !== $repass) {
        header("Location: dangnhap.php");
        exit();
    }

    $sql = "SELECT * FROM signup WHERE account = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $account);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        header("Location: dangnhap.php");
        exit();
    }

    // Hash password
    $pass = password_hash($pass, PASSWORD_DEFAULT);

    $sql = "INSERT INTO signup (hovaten, email, account, pass, repass)
           VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $hovaten, $email, $account, $pass, $repass);
    mysqli_stmt_execute($stmt);

    // Retrieve the username from the database
    $username = $hovaten;
    echo "<script>";
    echo "var username = '" . $username . "';";
    echo "document.getElementById('username').innerHTML = 'Hi, ' + username;";
    echo "document.getElementById('userDiv').style.display = 'block';";
    echo "</script>";

    //echo "Successful registration!";
} else {
    header("Location: dangnhap.php");
    exit();
}
?>
<div>
    <p>Bạn đã đăng kí thành công</p>
    <a href="dangnhap.php">Quay trở lại trang đăng nhập</a>
</div>
<br>
