<h1 class="h2 mb-3">Data Penjualan</h1>

<div class="d-flex mb-3">
    <?php 
        date_default_timezone_set("Asia/Jakarta");
        $sql = "SELECT * FROM penjualan";
        if(isset($_GET['s'])){
            $start=date($_GET['s']);
            $sql .= " WHERE tgl_pesan >= DATE('$start')";
        }else{
            $start=date("Y-m-01");
        }

        if(isset($_GET['e'])){
            $end=date($_GET['e']);
            $sql .= " AND tgl_pesan <= DATE('$end')";
        }else{
            $end=date("Y-m-d");
        }
    ?>
    <input type="date" class="form-control w-50" id="start" value="<?php echo $start;?>" onchange="start()">
    <input type="date" class="form-control w-50" id="end" value="<?php echo $end;?>" onchange="end()">
</div>

<div class="d-flex">
    <select name="kolom"  class="form-select w-25"  id="kolom" value="username">
        <option value="kode_penjualan" id="opsikode_penjualan">Kode</option>
        <option value="username" id="opsiusername">Username</option>
    </select>
  
    <input type="text" class="form-control w-100" placeholder="Cari penjualan..." id="cari" autocomplete="off">
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" onclick="cari()" id="btncari">Cari</button>
    </div>
    </div>
    <font color="gray"><b id="info">*Tekan kolom dengan <span class="link">tulisan berwarna biru dan digaris bawahi</span> untuk menampilkan informasi</b></font>
<br><br>
<table class="table table-bordered table-hover bg-white mw-100">
  <thead class="thead">
    <tr>
        <th width="10%">Kode Pesanan</th>
        <th>Pemesan</th>
        <th>Alamat Terima</th>
        <th>Tgl.Pesan</th>
        <th>Barang Dijual</th>
        <th>Tgl.Estimasi</th>
        <th>Tgl.Antar</th>
        <th>Tgl.Terima</th>
        <th>Status</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    include('../config/koneksi.php');

    $kolom="";
    if (isset($_GET["kolom"])){
      $kolom=$_GET["kolom"];
      $cari=$_GET["cari"];
      $sql .="AND $kolom LIKE '$cari%'";
      
  ?>
    <script>
      document.getElementById(<?php echo "'opsi".$kolom."'";?>).selected=true;
      document.getElementById("cari").value=<?php echo "'$cari'";?>;
    </script>
  <?php
    }

    $query = mysqli_query($conn, $sql." ORDER BY kode_penjualan DESC");
    while($data = mysqli_fetch_array($query)){
  ?>

    <tr>
        <td onclick="rincian('<?php echo $data['kode_penjualan']; ?>')"><?php echo $data['kode_penjualan']; ?></td>
        <td onclick="rincian('<?php echo $data['kode_penjualan']; ?>')"><?php echo $data['username']; ?></td>
        <td onclick="rincian('<?php echo $data['kode_penjualan']; ?>')"><?php echo $data['alamat_terima']; ?></td>
        <td onclick="rincian('<?php echo $data['kode_penjualan']; ?>')"><?php echo date_format(date_create($data['tgl_pesan']),"d/m/Y"); ?></td>
        <td onclick="rincian('<?php echo $data['kode_penjualan']; ?>')"><?php
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
        <td onclick="estimasi('<?php echo $data['kode_penjualan']; ?>')" class="link"><?php if($data['tgl_estimasi']=="0000-00-00"){echo "Belum ditentukan";}else{echo date_format(date_create($data['tgl_estimasi']),"d/m/Y");} ?></td>
        <td onclick="antar('<?php echo $data['kode_penjualan']; ?>')" class="link"><?php if($data['tgl_antar']=="0000-00-00"){echo "Belum diantar";}else{echo date_format(date_create($data['tgl_antar']),"d/m/Y");} ?></td>
        <?php if($data['tgl_terima']=="0000-00-00"){echo "<td>Belum diterima</td>";}else{ echo "<td class='link' onclick='terima(".$data['kode_penjualan'].")'>".date_format(date_create($data['tgl_terima']),"d/m/Y")."</td>"; } ?>
        <td onclick="rincian('<?php echo $data['kode_penjualan']; ?>')"><b>
            <?php 
            if($data['status']=="Belum bayar"){
                echo "<p class='text-danger'>Belum bayar</p>";
            } 
            if($data['status']=="Dikemas"){
                echo "<p class='text-primary'>Sudah bayar</p>";
            } 
            if($data['status']=="Diantar"){
                echo "<p class='text-warning'>Diantar</p>";
            } 
            if($data['status']=="Diterima"){
                echo "<p class='text-success'>Diterima</p>";
            } 
            if($data['status']=="Kadaluarsa"){
                echo "<p class='text-secondary'>Kadaluarsa</p>";
            } 
            ?></b>
        </td>
    </tr>
    
        <!-- modal penjualan -->
        <div class="modal fade modal-lg" id="rincian<?php echo $data['kode_penjualan']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col"><b>Pemesan</b></div>
                        <div class="col-8"><?php echo $data['username']; ?> (<?php echo $data['nohp_terima']; ?>)</div>
                    </div>
                    <div class="row">
                        <div class="col"><b>Tanggal Pesan</b></div>
                        <div class="col-8"><?php echo $data['tgl_pesan']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col"><b>Jasa Pengantaran</b></div>
                        <div class="col-8"><?php echo $data['nama_jasa_antar']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col"><b>Paket Pengantaran</b></div>
                        <div class="col-8"><?php echo $data['paket_pengantaran']; ?></div>
                    </div><br><br>
                    <div class="row">
                        <div class="col"><b>Nama Barang</b></div>
                        <div class="col"><b>Berat Barang</b></div>
                        <div class="col"><b>Jumlah</b></div>
                        <div class="col"><b>Harga Satuan</b></div>
                        <div class="col"><b>Subtotal</b></div>
                    </div><hr>
                    <?php
                        $query2 = mysqli_query($conn, "SELECT * FROM penjualan_detail JOIN barang ON penjualan_detail.kode_barang = barang.kode_barang WHERE kode_penjualan = '".$data['kode_penjualan']."'");
                        while($data2 = mysqli_fetch_array($query2)){
                    ?>
                        <div class="row">
                            <div class="col"><?php echo $data2['nama_barang']; ?></div>
                            <div class="col"><?php echo $data2['berat_barang']." ".$data2['satuan_berat']; ?></div>
                            <div class="col"><?php echo $data2['jumlah']; ?></div>
                            <div class="col"><?php echo "Rp ".number_format($data2['harga'],2,',','.'); ?></div>
                            <div class="col"><?php echo "Rp ".number_format($data2['jumlah']*$data2['harga'],2,',','.'); ?></div>
                        </div>
                    <?php } ?>
                    <hr><p align="right"><b>Total Harga </b><br><?php echo "Rp ".number_format($data['total_harga_barang'],2,',','.'); ?></p>
                </div>
                
                </div>
            </div>
        </div>

        <!-- modal estimasi -->
        <div class="modal fade modal-lg" id="estimasi<?php echo $data['kode_penjualan']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tanggal Estimasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="#" method="POST" autocomplete="off">
                  <div class="modal-body">
                    <input type="hidden" name="kode" value="<?php echo $data['kode_penjualan']; ?>">
                    <label for="nama" class="form-label">Tanggal Estimasi</label>
                    <?php if($data['tgl_estimasi']=="0000-00-00"){ ?>
                        <input type="date" class="form-control" name="tgl_estimasi" value="<?php echo date("Y-m-d");?>">
                    <?php }else{ ?>
                        <input type="date" class="form-control" name="tgl_estimasi" value="<?php echo $data['tgl_estimasi'];?>">
                    <?php } ?>
                    
                  </div>

                  <div class="modal-footer">
                    <button class="btn oren" style="width:50%;" name="simpanestimasi">Simpan</button>
                  </div>
                </form>
                
                </div>
            </div>
        </div>

        <!-- modal terima -->
        <div class="modal fade modal-lg" id="terima<?php echo $data['kode_penjualan']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti Terima</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <?php 
                        echo "<center class='m-2'><img src='gambar/buktiterima/".$data['bukti_terima']."' width='30%'></center>";
                    ?>
                
                </div>
            </div>
        </div>
        
        <!-- modal antar -->
        <div class="modal fade modal-lg" id="antar<?php echo $data['kode_penjualan']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tanggal Antar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="#" method="POST" enctype="multipart/form-data">
                  <div class="modal-body">
                    <input type="hidden" name="kode" value="<?php echo $data['kode_penjualan']; ?>">
                    <label for="nama" class="form-label">Tanggal Pengantaran</label>
                    <?php if($data['tgl_antar']=="0000-00-00"){ ?>
                        <input type="date" class="form-control" name="tgl_antar" value="<?php echo date("Y-m-d");?>">
                    <?php }else{ ?>
                        <input type="date" class="form-control" name="tgl_antar" value="<?php echo $data['tgl_antar'];?>">
                    <?php } ?>
                    
                    <label for="nama" class="form-label">Bukti Antar</label>
                    <input type="file" class="form-control mb-2" name="gambar" accept="image/*" style="border:1px solid #bebebe">
                    <?php 
                        if ($data['bukti_antar']!=""){
                            echo "<img src='gambar/buktiantar/".$data['bukti_antar']."' width='30%'>";
                        }
                    ?>
                  </div>
                    
                  <div class="modal-footer">
                    <button class="btn oren" style="width:50%;" name="simpanantar">Simpan</button>
                  </div>
                </form>
                
                </div>
            </div>
        </div>
    <?php 
        }
    ?>
  </tbody>
</table>

<script>
    function start(){
        var s = document.getElementById('start').value;
        location.href='?p=penjualan&s='+s;
    }
    function end(){
        var s = document.getElementById('start').value;
        var e = document.getElementById('end').value;
        location.href='?p=penjualan&s='+s+'&e='+e;
    }

    function rincian(id) {
        var myModal = new bootstrap.Modal(document.getElementById('rincian' + id));
        myModal.show();
    }

    function estimasi(id) {
        var myModal = new bootstrap.Modal(document.getElementById('estimasi' + id));
        myModal.show();
    }

    function antar(id) {
        var myModal = new bootstrap.Modal(document.getElementById('antar' + id));
        myModal.show();
    }

    function terima(id) {
        var myModal = new bootstrap.Modal(document.getElementById('terima' + id));
        myModal.show();
    }

    function cari(){
        var kolom = document.getElementById('kolom').value;
        var cari = document.getElementById('cari').value;
        var s = document.getElementById('start').value;
        var e = document.getElementById('end').value;

        location.href='?p=penjualan&s='+s+'&e='+e+'&p=penjualan&kolom='+kolom+'&cari='+cari;
    }

    document.getElementById("cari").addEventListener("keypress",function(event){if(event.key==="Enter"){event.preventDefault(); document.getElementById("btncari").click();}});
</script>

<?php 
    if(isset($_POST['simpanestimasi'])){
        $kode = $_POST['kode'];
        $estimasi = $_POST['tgl_estimasi'];

        $query = mysqli_query($conn, "UPDATE penjualan SET tgl_estimasi = '$estimasi' WHERE kode_penjualan = '$kode'");
  
          if($query){
              echo "<script>alert('Pesanan berhasil diedit'); location.href='?p=penjualan'; </script>";
          }else{
              echo "<script>alert('Pesanan gagal diedit')</script>";
          }
    }

    
    if(isset($_POST['simpanantar'])){
        $gambar = $_FILES["gambar"]["name"];
        $folder = "gambar/buktiantar/" . $gambar;
        $tmp = $_FILES["gambar"]["tmp_name"];
        move_uploaded_file($tmp, $folder);

        $kode = $_POST['kode'];
        $antar = $_POST['tgl_antar'];

        $query = mysqli_query($conn, "UPDATE penjualan SET tgl_antar = '$antar', status='Diantar', bukti_antar='$gambar' WHERE kode_penjualan = '$kode'");
  
          if($query){
              echo "<script>alert('Pesanan berhasil diedit'); location.href='?p=penjualan'; </script>";
          }else{
              echo "<script>alert('Pesanan gagal diedit')</script>";
          }
    }
?>
<style>
    .link{
        cursor: pointer;
        color: blue;
        text-decoration: underline;
    }
    .link:hover {
        background-color: lightgray;
        text-decoration: none;
    }
</style>