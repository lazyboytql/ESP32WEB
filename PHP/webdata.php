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
<html>
<head>
  <meta charset="utf-8">
  <title>Thông tin về Cảnh báo cháy rừng</title>
  <style>


* {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }

    h1 {
      text-align: center;
      margin-top: 20px;
      color: #333;
    }

    #data-container {
      max-width: 900px;
      margin: 20px auto;
      background-color: #fff;
      border: 1px solid #ccc;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    #temperature,
    #flame,
    #smoke,
    #longitude,
    #latitude {
      margin-bottom: 10px;
      margin-left: 20px;
      font-size: 18px;
    }

    #temperature {
      color: #3498db;
    }

    #flame {
      color: #e74c3c;
    }

    #smoke {
      color: #95a5a6;
    }

    #longitude,
    #latitude {
      color: #27ae60;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
      font-size: 14px;
    }

    th,
    td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
      color: #333;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    td:nth-child(5),
    td:nth-child(6) {
      font-weight: bold;
      color :#000000;
    }

    td:nth-child(5) {
      background-color: #FFFFFF;
      color: #000000;
    }

    td:nth-child(6) {
      background-color: ##FFFFFF;
      color: #000000;
    }

    .location-link {
      color: #3498db;
      cursor: pointer;
      text-decoration: underline;
    }

    /* Responsive */
    @media only screen and (max-width: 600px) {
      #data-container {
        max-width: 100%;
        padding: 10px;
      }

      table {
        font-size: 12px;
      }

      #search-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 10px;
      }

      #search-input {
        width: 100%;
        margin-bottom: 10px;
      }

      button[type="submit"] {
        width: 100%;
      }

      h1 {
        color: black;
      }
    }

    .home-button {
       position: absolute;
       top: 10px;
       right: 10px;
       padding: 10px 20px;
       background-color: #CCCCCC;
       color: #000000;
       text-decoration: none;
       transition: background-color 0.3s;
}

.home-button:hover {
       background-color: #FF0000; 
       color: #FFFFFF;
}

    .php-code {
        color: black;
    }

#current-time {
      position: fixed;
      bottom: 10px;
      right: 10px;
      padding: 10px;
      background-color: #f2f2f2;
      border: 1px solid #ccc;
      font-size: 16px;
      font-weight: bold;
}
<td class="php-code"><?php echo $fireSensorStatus; ?></td>
<td class="php-code"><?php echo $smokeSensorStatus; ?></td>

  </style>
</head>
<body>
  <div id="data-container">
    <a href="http://ktmtk15.rf.gd/home.php" style="position: absolute; top: 10px; right: 10px;" class ="home-button">Trang chủ</a>
    <h1 style="color: black;">Dữ liệu hiện tại</h1>
    <div id="temperature">Nhiệt độ: --</div>
    <div id="flame">Trạng thái Cảm biến lửa: --</div>
    <div id="smoke">Trạng  thái Cảm biến khói: --</div>
    <div id="longitude">Kinh độ: --</div>
    <div id="latitude">Vĩ độ: --</div>
 <h1 style="text-align: center; margin-top: 30px; color: black;">Dữ liệu gần đây</h1>
   <table>
  <tbody id="data-body">
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

// Lấy 5 dữ liệu gần nhất từ bảng SensorData
$query = "SELECT * FROM SensorData ORDER BY timestamp DESC LIMIT 5";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Hiển thị tiêu đề bảng
    echo '<table>';
    echo '<tr>
        <th>Thời gian</th>
        <th>Nhiệt độ</th>
        <th>Vĩ độ</th>
        <th>Kinh độ</th>
        <th>Trạng thái cảm biến Lửa</th>
        <th>Trạng thái cảm biến khói</th>
    </tr>';

    // Hiển thị từng dòng dữ liệu trong bảng
    while ($row = $result->fetch_assoc()) {
        $timestamp = $row['timestamp'];
        $temperature = $row['temperature'];
        $longitude = $row['longitude'];
        $latitude = $row['latitude'];
        $fireSensorStatus = $row['fireSensorStatus'];
        $smokeSensorStatus = $row['smokeSensorStatus'];

        echo '<tr>';
        echo '<td>' . $timestamp . '</td>';
        echo '<td>' . $temperature . '</td>';
        echo '<td>' . $longitude . '</td>';
        echo '<td>' . $latitude . '</td>';
        echo '<td>' . $fireSensorStatus . '</td>';
        echo '<td>' . $smokeSensorStatus . '</td>';
        echo '</tr>';
    }

    // Đóng bảng
    echo '</table>';
} else {
    echo "Không có dữ liệu.";
}

// Đóng kết nối
$conn->close();
?>
  </tbody>
</table>

   <h1 style="text-align: center; margin-top: 30px; color: black;">Dữ liệu đang cập nhật</h1>
<table>
  <tr>
        <th>Thời gian</th>
        <th>Nhiệt độ</th>
        <th>Vĩ độ</th>
        <th>Kinh độ</th>
        <th>Trạng thái cảm biến Lửa</th>
        <th>Trạng thái cảm biến khói</th>
        <th>Vị trí</th>
  </tr>
  <tbody id ="data-body2"></tbody>
</table>
<div id="current-time"></div>


   <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-database.js"></script>
   <script>
     // Khởi tạo firebase
     var firebaseConfig = {
      apiKey: "AIzaSyB_WbW07r2Rdrto0-31RRUy6UTtFJpEr0k",
            authDomain: "esp-32-4c1fe.firebaseapp.com",
            databaseURL: "https://esp-32-4c1fe-default-rtdb.firebaseio.com",
            projectId: "esp-32-4c1fe",
            storageBucket: "esp-32-4c1fe.appspot.com",
            messagingSenderId: "445011573723",
            appId: "1:445011573723:web:596f97461a05a8f9d205fe",
     };
     firebase.initializeApp(firebaseConfig);

     // Reference to the Firebase database
     var database = firebase.database();

     // Function to format the timestamp
     function formatTimestamp(timestamp) {
       var date = new Date(timestamp);
       return date.toLocaleString();
     }

    

     // Update the real-time data in the div elements
function updateData(temperature, fireSensorStatus, smokeSensorStatus, longitude, latitude) {
  document.getElementById('temperature').textContent = 'Nhiệt độ: ' + temperature;
  // Update fire sensor status
  var fireStatus = fireSensorStatus > 0 ? 'Có lửa' : 'Không có lửa';
  document.getElementById('flame').textContent = 'Trạng thái Cảm biến lửa: ' + fireStatus;
  // Update smoke sensor status
  var smokeStatus = smokeSensorStatus === 1 ? 'Có khói' : 'Không có khói';
  document.getElementById('smoke').textContent = 'Trạng thái Cảm biến khói: ' + smokeStatus;
  document.getElementById('longitude').textContent = 'Kinh độ: ' + longitude;
  document.getElementById('latitude').textContent = 'Vĩ độ: ' + latitude;
}

// Update the second table with data from Firebase
database.ref('/Cambien').on('value', function(snapshot) {
  var data = snapshot.val();
  var temperature = data.Nhietdo;
  var fireSensorStatus = data.Lua;
  var smokeSensorStatus = data.Khoi;

  // Get location data
  database.ref('/location').once('value', function(locationSnapshot) {
    var locationData = locationSnapshot.val();
    var longitude = locationData.Longitude;
    var latitude = locationData.Latitude;

    updateData(temperature, fireSensorStatus, smokeSensorStatus, longitude, latitude);
  });
});

// Function to update the second table with data
function updateTable2(timestamp, temperature, longitude, latitude, fireSensorStatus, smokeSensorStatus) {
  var tableBody = document.getElementById('data-body2');
  var row = tableBody.insertRow(0); // Insert a new row at the beginning

  var timestampCell = row.insertCell();
  timestampCell.textContent = formatTimestamp(timestamp);

  var temperatureCell = row.insertCell();
  temperatureCell.textContent = temperature;

  var longitudeCell = row.insertCell();
  longitudeCell.textContent = longitude;

  var latitudeCell = row.insertCell();
  latitudeCell.textContent = latitude;

  var fireSensorCell = row.insertCell();
  fireSensorCell.textContent = fireSensorStatus;
  fireSensorCell.style.backgroundColor = fireSensorStatus === 1 ; 

  var smokeSensorCell = row.insertCell();
  smokeSensorCell.textContent = smokeSensorStatus;
  smokeSensorCell.style.backgroundColor = smokeSensorStatus === 1 ;

  var locationCell = row.insertCell();
  var locationLink = document.createElement('span');
  locationLink.className = 'location-link';
  locationLink.textContent = 'Xem ngay';
  locationLink.addEventListener('click', function() {
    openGoogleMaps(longitude, latitude);
  });
  locationCell.appendChild(locationLink);

  if (tableBody.rows.length > 10) {
    tableBody.deleteRow(-1);
  }

  sendData(timestamp, temperature, longitude, latitude, fireSensorStatus, smokeSensorStatus);
}

// Function to send the data to the server
function sendData(timestamp, temperature, longitude, latitude, fireSensorStatus, smokeSensorStatus) {
  // Create a new XMLHttpRequest object
  var xhr = new XMLHttpRequest();

  // Construct the URL for the AJAX request
  var url = 'insert_data.php' + '?timestamp=' + encodeURIComponent(timestamp) +
    '&temperature=' + encodeURIComponent(temperature) +
    '&longitude=' + encodeURIComponent(longitude) +
    '&latitude=' + encodeURIComponent(latitude) +
    '&fireSensorStatus=' + encodeURIComponent(fireSensorStatus) +
    '&smokeSensorStatus=' + encodeURIComponent(smokeSensorStatus);

  // Open the request
  xhr.open('GET', url, true);

  // Set the onload event handler
  xhr.onload = function() {
    if (xhr.status === 200) {
      console.log('Data sent successfully');
    } else {
      console.log('Error sending data');
    }
  };

  // Send the request
  xhr.send();
}
// Update the second table with data from Firebase
database.ref('/Cambien/Nhietdo').on('value', function(temperatureSnapshot) {
  var temperature = temperatureSnapshot.val();

  if (temperature) {
    database.ref('/Cambien').once('value', function(snapshot) {
      var data = snapshot.val();
      var timestamp = Date.now();
      var fireSensorStatus = data.Lua;
      var smokeSensorStatus = data.Khoi;

      // Get location data
      database.ref('/location').once('value', function(locationSnapshot) {
        var locationData = locationSnapshot.val();
        var longitude = locationData.Longitude;
        var latitude = locationData.Latitude;

        updateTable2(timestamp, temperature, longitude, latitude, fireSensorStatus, smokeSensorStatus);
      });
    });
  }
});
 // Update the second table with data from Firebase every 5 seconds
setInterval(function() {
  database.ref('/Cambien/Nhietdo').once('value', function(temperatureSnapshot) {
    var temperature = temperatureSnapshot.val();

    if (temperature) {
      database.ref('/Cambien').once('value', function(snapshot) {
        var data = snapshot.val();
        var timestamp = Date.now();
        var fireSensorStatus = data.Lua;
        var smokeSensorStatus = data.Khoi;

        database.ref('/location').once('value', function(locationSnapshot) {
          var locationData = locationSnapshot.val();
          var longitude = locationData.Longitude;
          var latitude = locationData.Latitude;

          updateTable2(timestamp, temperature, longitude, latitude, fireSensorStatus, smokeSensorStatus);
        });
      });
    }
  });
}, 10000); 

     function openGoogleMaps(longitude, latitude) {
       var url = "https://www.google.com/maps?q=" + latitude + "," + longitude;
       window.open(url, "_blank");
     }

function updateTime() {
  var now = new Date();
  var year = now.getFullYear();
  var month = (now.getMonth() + 1).toString().padStart(2, '0');
  var day = now.getDate().toString().padStart(2, '0');
  var hours = now.getHours().toString().padStart(2, '0');
  var minutes = now.getMinutes().toString().padStart(2, '0');
  var seconds = now.getSeconds().toString().padStart(2, '0');
  var currentDate = day + '/' + month + '/' + year;
  var currentTime = hours + ':' + minutes + ':' + seconds;
  document.getElementById('current-time').textContent = 'Thời gian hiện tại: ' + currentDate + ' ' + currentTime;
}

setInterval(updateTime, 1000);


   </script>
</body>
</html>