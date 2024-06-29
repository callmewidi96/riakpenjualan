<img src="../icon/laporan.jpg" width="50%">

<h1 class="h2 mb-3" id="judul">Laporan Penjualan</h1>
<div class="row">
    <div class="col">
        <button type="button" class="btn btn-outline-success mb-3 w-100 skip" onclick="print()">Print Laporan</button>
    </div>
    <div class="col">
        <?php
            include('../config/koneksi.php');
            if($_GET['filter']=="harian"){ 
                $tgl=$_GET['tgl'];
                $h=substr($tgl,8);
                $b=substr($tgl,5,2);
                if($b==1){$b="Januari";}
                if($b==2){$b="Februari";}
                if($b==3){$b="Maret";}
                if($b==4){$b="April";}
                if($b==5){$b="Mei";}
                if($b==6){$b="Juni";}
                if($b==7){$b="Juli";}
                if($b==8){$b="Agustus";}
                if($b==9){$b="September";}
                if($b==10){$b="Oktober";}
                if($b==11){$b="November";}
                if($b==12){$b="Desember";}
                $t=substr($tgl,0,4);
                $query = mysqli_query($conn, "SELECT * FROM penjualan WHERE tgl_antar = '$tgl' AND (status = 'Diterima' OR status = 'Diantar') ORDER BY tgl_pesan DESC");
        ?>

            <input type="date" class="form-control w-50 skip" id="tgl" value="<?php echo $tgl;?>" onchange="tanggal()"> 
            <script>
                document.getElementById("judul").innerHTML+=" <?php echo $h." ".$b." ".$t;?>";
                function tanggal(){
                    let tgl=document.getElementById("tgl").value;
                    location.href='?p=laporanjual&filter=harian&tgl='+tgl; 
                }
            </script>      
        <?php
            }
            if($_GET['filter']=="tahunan"){
                $thn=$_GET['thn'];
                $query = mysqli_query($conn, "SELECT * FROM penjualan WHERE YEAR(tgl_antar) = $thn AND (status = 'Diterima' OR status = 'Diantar') ORDER BY tgl_pesan DESC");
        ?>
            <script>
                document.getElementById("judul").innerHTML+=" Tahun <?php echo $thn;?>";
            </script>
            <span class="form-select skip" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"><?php echo $thn;?></span>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1"> 
                <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=tahunan&thn=2023">2023</a></li>
                <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=tahunan&thn=2024">2024</a></li>
                <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=tahunan&thn=2045">2025</a></li>
                <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=tahunan&thn=2026">2026</a></li>
                <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=tahunan&thn=2027">2027</a></li>
            </ul>
        <?php
            }
            if($_GET['filter']=="bulanan"){
                $bln=$_GET['bln'];
                $query = mysqli_query($conn, "SELECT * FROM penjualan WHERE MONTH(tgl_antar) = $bln AND (status = 'Diterima' OR status = 'Diantar') ORDER BY tgl_pesan DESC");
                if($bln==1){$bln="Januari";}
                if($bln==2){$bln="Februari";}
                if($bln==3){$bln="Maret";}
                if($bln==4){$bln="April";}
                if($bln==5){$bln="Mei";}
                if($bln==6){$bln="Juni";}
                if($bln==7){$bln="Juli";}
                if($bln==8){$bln="Agustus";}
                if($bln==9){$bln="September";}
                if($bln==10){$bln="Oktober";}
                if($bln==11){$bln="November";}
                if($bln==12){$bln="Desember";}
        ?>
        <script>
            document.getElementById("judul").innerHTML+=" Bulan <?php echo $bln;?>";
        </script>
        <span class="form-select skip" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"><?php echo $bln;?></span>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1"> 
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=1">Januari</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=2">Februari</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=3">Maret</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=4">April</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=5">Mei</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=6">Juni</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=7">Juli</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=8">Agustus</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=9">September</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=10">Oktober</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=11">November</a></li>
            <li><a class="dropdown-item" href="index.php?p=laporanjual&filter=bulanan&bln=12">Desember</a></li>
        </ul>
        <?php
            }   
        ?>
    </div>
</div>
<style>
    .print{
        display: none;
    }

    @media print {
        html, body {
            width: 241.3mm;
            height:  304.8mm; 
        }

        .skip{
            display:none;
        }
        .print{
            display:flex;
        }
        .thead{
            color: white;
            background-color:#36454f;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
    }

    th {
        height: 50px;
        vertical-align: middle;
        text-align: center;
    }

</style>

<table class="table table-bordered table-hover bg-white mw-100">
  <thead class="thead">
    <tr>
        <th width="10%">Kode Pesanan</th>
        <th>Pemesan</th>
        <th>Alamat Terima</th>
        <th>Jasa</th>
        <th>Tgl.Pesan</th>
        <th>Barang Dijual</th>
        <th>Tgl.Antar</th>
        <th>Tgl.Terima</th>
        <th>Total Pembelian</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    include('../config/koneksi.php');
  ?>
<script>
    print(){
        window.print();
    }
</script>

<?php
    $total=0;

    while($data = mysqli_fetch_array($query)){
?>

    <tr>
        <td><?php echo $data['kode_penjualan']; ?></td>
        <td><?php echo $data['username']; ?></td>
        <td><?php echo $data['alamat_terima']; ?></td>
        <td><?php echo $data['nama_jasa_antar']; ?></td>
        <td><?php echo date_format(date_create($data['tgl_pesan']),"d/m/Y"); ?></td>
        <td><?php
                $query2 = mysqli_query($conn, "SELECT * FROM penjualan_detail JOIN barang ON penjualan_detail.kode_barang = barang.kode_barang WHERE kode_penjualan = '".$data['kode_penjualan']."'");
                while($data2 = mysqli_fetch_array($query2)){
            ?>
                <p>
                    <?php echo $data2['nama_barang']; ?> &times;<b>(<?php echo $data2['jumlah']; ?>)</b>
                </p>
            <?php
                }
            ?>
        </td>
        <td><?php if($data['tgl_antar']=="0000-00-00"){echo "Belum diantar";}else{echo date_format(date_create($data['tgl_antar']),"d/m/Y");} ?></td>
        <td><?php if($data['tgl_terima']=="0000-00-00"){echo "Belum diterima";}else{ echo date_format(date_create($data['tgl_terima']),"d/m/Y"); } ?></td>
        <td><?php echo "Rp ".number_format($data['total_harga_barang'],2,',','.'); $total+=$data['total_harga_barang'];?></td>
    </tr>
    
    <?php 
        }
    ?>
  </tbody>
</table>
<?php echo "<p align='right'><b>Total Pendapatan:</b> Rp ".number_format($total,2,',','.')."</p>"; ?>
<div class="print">
    <table width="100%">
        <tr>
            <td width="50%"></td>
            <td>
                <?php
                    date_default_timezone_set("Asia/Jakarta");
                    $tgl=date_format(date_create(date("Y/m/d")),"Y-m-d");
                    $h=substr($tgl,8);
                    $b=substr($tgl,5,2);
                    if($b==1){$b="Januari";}
                    if($b==2){$b="Februari";}
                    if($b==3){$b="Maret";}
                    if($b==4){$b="April";}
                    if($b==5){$b="Mei";}
                    if($b==6){$b="Juni";}
                    if($b==7){$b="Juli";}
                    if($b==8){$b="Agustus";}
                    if($b==9){$b="September";}
                    if($b==10){$b="Oktober";}
                    if($b==11){$b="November";}
                    if($b==12){$b="Desember";}
                    $t=substr($tgl,0,4);
                    echo $h." ".$b." ".$t;
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Admin<br><br><br><br><?php echo $_SESSION['user']; ?>
            </td>
            <td>
                Direktur<br><br><br><br>Valentinus Heri
            </td>
        </tr>
    </table>
</div>