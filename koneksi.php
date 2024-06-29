<?php 
    $conn = mysqli_connect("localhost", "root", "", "cvriakbumi");

    if(!$conn){
        echo mysqli_error($conn);
        exit();
    }
    date_default_timezone_set("Asia/Jakarta");
?>