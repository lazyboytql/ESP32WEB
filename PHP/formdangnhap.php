
<?php
session_start();
include 'config.php';
if(isset($_POST['submit'])&& $_POST['account'] != '' && $_POST['pass'] != ''){
    $account = mysqli_real_escape_string($conn, $_POST["account"]);
    $pass = mysqli_real_escape_string($conn, $_POST["pass"]);
    
    $sql = "SELECT * FROM signup WHERE account ='$account'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if(password_verify($pass, $row['pass'])) {
            echo "Logged in successfully";
            header("Location:home.php");
            $_SESSION['account'] =$row['account'];
            $_SESSION['hovaten'] =$row['hovaten'];
        } else {
            echo "Wrong password";
        }
    } else {
        echo "User not found";
    }
}
?>
