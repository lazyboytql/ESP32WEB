<?php
// Đặt múi giờ cho Bangkok/Hanoi
date_default_timezone_set('Asia/Bangkok');

// Kết nối tới cơ sở dữ liệu
$servername = "sql301.infinityfree.com";
$username = "if0_34405520";
$password = "eNwHynu5eD5yF";
$dbname = "if0_34405520_ktmtk15";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy giá trị dữ liệu từ URL
$temperature = $_GET['temperature'];
$longitude = $_GET['longitude'];
$latitude = $_GET['latitude'];
$fireSensorStatus = $_GET['fireSensorStatus'];
$smokeSensorStatus = $_GET['smokeSensorStatus'];

// Lấy giá trị timestamp hiện tại
$timestamp = date('Y-m-d H:i:s');

// Chuẩn bị câu truy vấn INSERT
$stmt = $conn->prepare("INSERT INTO SensorData (timestamp, temperature, longitude, latitude, fireSensorStatus, smokeSensorStatus) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdddsd", $timestamp, $temperature, $longitude, $latitude, $fireSensorStatus, $smokeSensorStatus);

// Thực thi câu truy vấn
if ($stmt->execute()) {
    echo "Dữ liệu đã được lưu thành công.";
} else {
    echo "Lỗi: " . $stmt->error;
}

// Đóng câu truy vấn
$stmt->close();

// Đợi 10 giây trước khi kết thúc chương trình
sleep(15);

// Đóng kết nối
$conn->close();
?>