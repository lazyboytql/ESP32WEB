<?php

$servername = "sql301.infinityfree.com";
$username = "if0_34405520";
$password = "eNwHynu5eD5yF";
$dbname = "if0_34405520_ktmtk15";


// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu từ biểu mẫu
$name = $_POST['Name'];
$email = $_POST['Email'];
$message = $_POST['Message'];

// Thêm timestamp
$timestamp = date('Y-m-d H:i:s');

// Chuẩn bị truy vấn INSERT để chèn dữ liệu vào bảng trong cơ sở dữ liệu
$sql = "INSERT INTO ykien (`Time`, `name`, `email`, `loinhan`) VALUES ('$timestamp', '$name', '$email', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "Gửi phản hồi thành công!";
} else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
}
?>
