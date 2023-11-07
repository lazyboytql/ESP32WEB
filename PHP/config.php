<?php

    $conn = mysqli_connect("sql301.infinityfree.com", "if0_34405520", "eNwHynu5eD5yF", "if0_34405520_ktmtk15");
    mysqli_set_charset($conn, "utf8");

    if(!$conn){
        die("khong the ket noi");
    }
?>