<?php 
    session_start();

    if((!isset($_SESSION['role']))||($_SESSION['role']!="adm")){
        header('location:../index.php');
    }
?>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/dashboard.css" rel="stylesheet">
<script src="../js/bootstrap.bundle.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/x-icon" href="/../icon/icon.png">
<header class="navbar navbar-dark fixed-top shadow skip">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">CV Riak Bumi</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" style="right:20px" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</header>
