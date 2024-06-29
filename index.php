<?php 
    include('header.php'); 
    date_default_timezone_set("Asia/Jakarta");
    $bln=date_format(date_create(date("Y/m/d")),"m");
    $thn=date_format(date_create(date("Y/m/d")),"Y");
    $tgl=date_format(date_create(date("Y/m/d")),"Y-m-d");
    
?>

<html>
    <body class="pt-5">
        
        <style>
            .navbar{
                height:3em;
                background:#36454F;
            }
            
            .sidemenu{
                background:#36454F;
            }

            nav {
                position:fixed;
                height: 100%;
            }

            .nav-link{
                color:#A4ABB0;
            }

            .nav-link:hover{
                color:#CDD2B9;
            }

            .on>a{
                background:#FB8B24;
                border-radius:25px;
                color:white;
            }
            
            .oren{
                background:#FB8B24;
                color:white;
                border-radius:25px;
            }
            .oren:hover{
                background:#dd8028;
                color:white;
            }
            
            .thead{
                background:#36454F;
                color:white;
            }
        </style>

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="sidemenu col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item" id="barang">
                            <a class="nav-link" href="?p=barang">
                                Data Barang
                            </a>
                        </li>
                        <li class="nav-item" id="kategori">
                            <a class="nav-link" href="?p=kategori">
                                Kategori
                            </a>
                        </li>
                        <li class="nav-item" id="user">
                            <a class="nav-link" href="?p=user">
                                Data User
                            </a>
                        </li>
                        <li class="nav-item" id="penjualan">
                            <a class="nav-link" href="?p=penjualan">
                                Data Penjualan
                            </a>
                        </li>
                        <li class="nav-item" id="pembelian">
                            <a class="nav-link" href="?p=pembelian">
                                Data Pembelian
                            </a>
                        </li>
                        <li class="nav-item" id="laporanjual">
                            <a class="nav-link" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Laporan Penjualan</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=harian&tgl=<?php echo $tgl;?>">Harian</a></li>
                                <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=<?php echo $bln;?>">Bulanan</a></li>
                                <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=tahunan&thn=<?php echo $thn;?>">Tahunan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item" id="laporanbeli">
                            <a class="nav-link" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">Laporan Pembelian</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <li><a class="dropdown-item" href="index.php?p=laporanbeli&filter=harian&tgl=<?php echo $tgl;?>">Harian</a></li>
                                <li><a class="dropdown-item" href="index.php?p=laporanbeli&filter=bulanan&bln=<?php echo $bln;?>">Bulanan</a></li>
                                <li><a class="dropdown-item" href="index.php?p=laporanbeli&filter=tahunan&thn=<?php echo $thn;?>">Tahunan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item" id="tgl_pesan">
                            <a class="nav-link" href="?p=jasaantar">
                                Data Jasa Pengantaran
                            </a>
                        </li>
                        <br><br>
                        <li class="nav-item" id="profil">
                            <a class="nav-link" href="?p=profil">
                                Profil
                            </a>
                        </li>
                        <li class="nav-item" id="logout">
                            <a class="nav-link" href="../login.php">Log Out</a>
                        </li>
                    </ul>
                </div>
                </nav>
            </div>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3 h-100 p-2">
        <?php 
            $page = $_GET['p'];
            echo "<script>document.getElementById('$page').className='on';</script>";
            
            switch($page){
                case 'barang': {
                    include('page/barang.php'); 
                    break;
                }
                case 'kategori': {
                    include('page/kategori.php'); 
                    break;
                }
                case 'user': {
                    include('page/user.php'); 
                    break;
                }
                case 'penjualan': {
                    include('page/penjualan.php'); 
                    break;
                }
                case 'pembelian': {
                    include('page/pembelian.php'); 
                    break;
                }
                case 'laporanjual': {
                    include('page/laporanjual.php'); 
                    break;
                }
                case 'laporanbeli': {
                    include('page/laporanbeli.php'); 
                    break;
                }
                case 'jasaantar': {
                    include('page/jasaantar.php'); 
                    break;
                }
                case 'profil': {
                    include('page/profil.php'); 
                    break;
                }
            }
        ?>
        <br>
        </main>
        
    </body>
</html>