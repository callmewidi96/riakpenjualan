<h1 class="h2 mb-3">Data Pembelian</h1>
<button type="button" class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Data</button>

<div class="d-flex mb-3">
    <?php 
        date_default_timezone_set("Asia/Jakarta");
        $sql = "SELECT * FROM pembelian";
        if(isset($_GET['s'])){
            $start=date($_GET['s']);
            $sql .= " WHERE tgl_pembelian >= DATE('$start')";
        }else{
            $start=date("Y-m-01");
        }

        if(isset($_GET['e'])){
            $end=date($_GET['e']);
            $sql .= " AND tgl_pembelian <= DATE('$end')";
        }else{
            $end=date("Y-m-d");
        }
    ?>
    <input type="date" class="form-control w-50" id="start" value="<?php echo $start;?>" onchange="start()">
    <input type="date" class="form-control w-50" id="end" value="<?php echo $end;?>" onchange="end()">
</div>

<div class="d-flex">
    <select name="kolom"  class="form-select w-25"  id="kolom" value="no_faktur">
        <option value="no_faktur" id="opsino_faktur">No.Faktur</option>
        <option value="pembeli" id="opsipembeli">Pembeli</option>
        <option value="supplier" id="opsisupplier">Supplier</option>
    </select>
  
    <input type="text" class="form-control w-100" placeholder="Cari pembelian..." id="cari" autocomplete="off">
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" onclick="cari()" id="btncari">Cari</button>
    </div>
</div><br>

<table class="table table-bordered table-hover bg-white mw-100">
  <thead class="thead">
    <tr>
        <th width="10%">No.Faktur</th>
        <th>Pembeli</th>
        <th>Supplier</th>
        <th>Tgl.Pembelian</th>
        <th>Barang Dibeli</th>
        <th>Total Harga</th>
        <th>Aksi</th>
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

    $query = mysqli_query($conn, $sql." ORDER BY no_faktur DESC");
    while($data = mysqli_fetch_array($query)){
  ?>

    <tr>
        <td onclick="rincian('<?php echo $data['no_faktur']; ?>')"><?php echo $data['no_faktur']; ?></td>
        <td onclick="rincian('<?php echo $data['no_faktur']; ?>')"><?php echo $data['pembeli']; ?></td>
        <td onclick="rincian('<?php echo $data['no_faktur']; ?>')"><?php echo $data['supplier']; ?></td>
        <td onclick="rincian('<?php echo $data['no_faktur']; ?>')"><?php echo $data['tgl_pembelian']; ?></td>
        <td onclick="rincian('<?php echo $data['no_faktur']; ?>')"><?php
                $query2 = mysqli_query($conn, "SELECT * FROM pembelian_detail WHERE no_faktur = '".$data['no_faktur']."'");
                while($data2 = mysqli_fetch_array($query2)){
            ?>
             <p>
                    <?php echo $data2['nama_barang']; ?> | <?php echo $data2['berat_barang']; ?><?php echo $data2['satuan_berat']; ?> &times;<b>(<?php echo $data2['jumlah_beli']; ?>)</b>
                </p>
            <?php
                }
            ?>
        </td>
        <td onclick="rincian('<?php echo $data['no_faktur']; ?>')"><?php echo "Rp ".number_format($data['total_harga_pembelian'],2,',','.'); ?></td>
        <td>
          <button type="button" class="btn btn-outline-info btn-sm"onclick="modalEdit(<?php echo $data['no_faktur']; ?>)">Edit</button>
          <button type="button" class="btn btn-outline-danger btn-sm"onclick="modalHapus(<?php echo $data['no_faktur']; ?>)">Hapus</button>
        </td>
      </tr>
    
        <!-- modal pembelian -->
        <div class="modal fade modal-lg" id="rincian<?php echo $data['no_faktur']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col"><b>No Faktur</b></div>
                        <div class="col-8"><?php echo $data['no_faktur']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col"><b>Pemesan</b></div>
                        <div class="col-8"><?php echo $data['pembeli']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col"><b>Supplier</b></div>
                        <div class="col-8"><?php echo $data['supplier']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col"><b>Tanggal Pesan</b></div>
                        <div class="col-8"><?php echo $data['tgl_pembelian']; ?></div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col"><b>Nama Barang</b></div>
                        <div class="col"><b>Berat Barang</b></div>
                        <div class="col"><b>Jumlah</b></div>
                        <div class="col"><b>Harga Beli</b></div>
                        <div class="col"><b>Subtotal</b></div>
                    </div><hr>
                    <?php
                        $query2 = mysqli_query($conn, "SELECT * FROM pembelian_detail WHERE no_faktur = '".$data['no_faktur']."'");
                        while($data2 = mysqli_fetch_array($query2)){
                    ?>
                        <div class="row">
                            <div class="col"><?php echo $data2['nama_barang']; ?></div>
                            <div class="col"><?php echo $data2['jumlah_beli']; ?></div>
                            <div class="col"><?php echo $data2['berat_barang']; ?> <?php echo $data2['satuan_berat']; ?></div>
                            <div class="col"><?php echo "Rp ".number_format($data2['harga_beli'],2,',','.'); ?></div>
                            <div class="col"><?php echo "Rp ".number_format($data2['jumlah_beli']*$data2['harga_beli'],2,',','.'); ?></div>
                        </div>
                    <?php 
                      }
                    ?>
                    <hr><p align="right"><b>Total Harga </b><br><?php echo "Rp ".number_format($data['total_harga_pembelian'],2,',','.'); ?></p>
                </div>
                
                </div>
            </div>
        </div>
        
        <!-- modal edit  -->
        <div class="modal fade" id="modalEdit<?php echo $data['no_faktur']; ?>" 
          tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Data Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="./page/pembelian_proses.php" method="POST" autocomplete="off" enctype="multipart/form-data">
              <div class="modal-body">
                <?php $data2=mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pembelian WHERE no_faktur = '".$data['no_faktur']."'")); ?>
                  <input type="hidden" name="kode" value="<?php echo $data['no_faktur']; ?>">
                  <div class="row">
                    <div class="col">
                        <label for="nama" class="form-label">No Faktur</label>
                        <input type="text" class="form-control" value="<?php echo $data2['no_faktur']; ?>" readonly>
                    </div>
                    <div class="col">
                        <label for="nama" class="form-label">Supplier</label>
                        <input type="text" name="editsupplier" class="form-control" value="<?php echo $data2['supplier']; ?>">
                    </div>
                    <div class="col">
                      <label for="nama" class="form-label">Pembeli</label>
                      <input type="text" name="editpembeli" class="form-control" value="<?php echo $data2['pembeli']; ?>">
                    </div>
                    
                  </div><br><br>
                  
                  <div class="row">
                      <div class="col"><b>Nama Barang</b></div>
                      <div class="col"><b>Berat Barang</b></div>
                      <div class="col"><b>Jumlah</b></div>
                      <div class="col"><b>Harga Beli</b></div>
                  </div><hr>
                
                  <datalist id="listbarang">
                  <?php
                    $query2 = mysqli_query($conn, "SELECT * FROM barang");
                    while($data2 = mysqli_fetch_array($query2)){
                  ?>
                    <option value="<?php echo $data2['nama_barang']; ?>"><?php echo $data2['nama_barang']; ?></option>
                  <?php 
                    }
                  ?>
                  </datalist>

                  <?php
                    $i=1;
                    $query2 = mysqli_query($conn, "SELECT * FROM pembelian_detail WHERE no_faktur = '".$data['no_faktur']."'");
                    while($data2 = mysqli_fetch_array($query2)){
                  ?>
                        <div class="row">
                            <div class="col"><input class="form-control" name="editnama<?php echo $i; ?>" value="<?php echo $data2['nama_barang']; ?>" list="listbarang" ></div>
                            <div class="col">
                                  <div class="input-group">
                                    <input type="number" class="form-control w-50" id="editberatbarang" name="editberatbarang<?php echo $i; ?>" value="<?php echo $data2['berat_barang']; ?>"> 
                                    <select name="editsatuan<?php echo $i; ?>"  class="form-select"  id="editsatuan" value="kg">
                                      <?php 
                                        if($data2['satuan_berat']=="kg"){
                                          echo "<option value='kg' id='kg' selected>kg</option>";
                                        }else{
                                          echo "<option value='kg' id='kg'>kg</option>";
                                        }

                                        if($data2['satuan_berat']=="g"){
                                          echo "<option value='g' id='g' selected>g</option>";
                                        }else{
                                          echo "<option value='g' id='g'>g</option>";
                                        }

                                        if($data2['satuan_berat']=="l"){
                                          echo "<option value='l' id='l' selected>l</option>";
                                        }else{
                                          echo "<option value='l' id='l'>l</option>";
                                        }
                                        
                                        if($data2['satuan_berat']=="ml"){
                                          echo "<option value='ml' id='ml' selected>ml</option>";
                                        }else{
                                          echo "<option value='ml' id='ml'>ml</option>";
                                        }
                                      ?>
                                    </select>
                                  </div>
                            </div>
                            <div class="col"><input class="form-control" name="editjumlah<?php echo $i; ?>" value="<?php echo $data2['jumlah_beli']; ?>"></div>
                            <div class="col"><input class="form-control" name="editharga<?php echo $i; ?>" value="<?php echo $data2['harga_beli']; ?>"></div>
                        </div>
                  <?php
                      $i+=1;
                    }
                  ?>
              </div>
              <div class="modal-footer">
                <button class="btn oren" style="width:50%;" name="edit">Perbaharui</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <!-- modal hapus  -->
        <div class="modal fade" id="modalHapus<?php echo $data['no_faktur']; ?>" 
          tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Hapus Data Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
              <div class="modal-body">
                <input type="hidden" name="kode" value="<?php echo $data['no_faktur']; ?>">
                Yakin ingin menghapus data pembelian?
              </div>
              <div class="modal-footer">
                <button class="btn oren" style="width:50%;" name="hapus">Hapus</button>
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


<!-- modal tambah pembelian -->

<div class="modal fade modal-lg" id="modalTambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Pembelian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
        <div class="modal-body">
          <div class="row">
            <div class="col">
                <label for="nama" class="form-label">No. Faktur</label>
                <input type="text" id="faktur" class="form-control" readonly value="<?php echo substr(date_format(date_create("Now"), "ymdHisU"),0,13);?>">
            </div>
            <div class="col">
                <label for="nama" class="form-label">Supplier</label>
                <input type="text" id="supplier" class="form-control">
            </div>
            <div class="col">
              <label for="nama" class="form-label">Pembeli</label>
              <input type="text" id="pembeli" class="form-control">
            </div>
            
          </div>

          <div class="row">
            <div class="col">
              <label for="nama" class="form-label">Barang Dibeli</label>
              <input name="namabarang" list="listbarang" id="namabarang" class="form-select">
                
                <datalist id="listbarang">
              <?php
                $query2 = mysqli_query($conn, "SELECT * FROM barang");
                while($data2 = mysqli_fetch_array($query2)){
              ?>
                <option value="<?php echo $data2['nama_barang']; ?>"><?php echo $data2['nama_barang']; ?></option>
              <?php 
                }
              ?>
              </datalist>
            </div>

            <div class="col">
              <label for="nama" class="form-label">Berat Barang</label>
                  <div class="input-group">
                    <input type="number" class="form-control w-50" id="beratbarang" name="beratbarang"> 
                    <select name="satuan"  class="form-select"  id="satuan" value="kg">
                      <?php 
                        if($data['satuan_berat']=="kg"){
                          echo "<option value='kg' id='kg' selected>kg</option>";
                        }else{
                          echo "<option value='kg' id='kg'>kg</option>";
                        }

                        if($data['satuan_berat']=="g"){
                          echo "<option value='g' id='g' selected>g</option>";
                        }else{
                          echo "<option value='g' id='g'>g</option>";
                        }

                        if($data['satuan_berat']=="l"){
                          echo "<option value='l' id='l' selected>l</option>";
                        }else{
                          echo "<option value='l' id='l'>l</option>";
                        }
                        
                        if($data['satuan_berat']=="ml"){
                          echo "<option value='ml' id='ml' selected>ml</option>";
                        }else{
                          echo "<option value='ml' id='ml'>ml</option>";
                        }
                      ?>
                    </select>
                  </div>
            </div>

            <div class="col">
              <label for="harga" class="form-label">Jumlah Beli</label>
              <div class="input-group mb-3">
                <input type="number" class="form-control" id="jumlahbeli" name="jumlahbeli">
              </div>
            </div>

          </div>
          <div class="row">
            <div class="col">
              <label for="harga" class="form-label">Harga Satuan</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Rp.</span>
                </div>

                <input type="number" class="form-control" id="hargabeli" name="hargabeli">
                
              </div>
            </div>

            <div class="col">
              <label for="harga" class="form-label"><font color="white">Tambah</font></label>
              <button class="btn btn-success form-control" onclick="tambahbeli()">Tambah</button>
            </div>

          </div>
        </div>

      <form action="./page/pembelian_proses.php" method="POST" autocomplete="off">
      <div class="form-control">
        <b>
        <div class="row">
          <div class="col">Nama Barang</div>
          <div class="col">Berat Barang</div>
          <div class="col">Jumlah Beli</div>
          <div class="col">Harga Beli</div>
          <div class="col">Subtotal</div>
        </div>
        <hr>
        </b>
        <div id="listitem"></div>
        
        <hr>
       <p align="right" class="form-control"><b>Total Harga: </b> <span id="total">0</span></p>
       <input type="text" name="total" id="phptotal" value="0" hidden>
       <input type="text" name="item" id="phpitem" value="0" hidden>
       <input type="text" name="supplier" id="phpsupplier" value="" hidden>
       <input type="text" name="pembeli" id="phppembeli" value="" hidden>
       <input type="text" name="faktur" id="phpfaktur" value="" hidden>
       <div id="inputphp">
          
        </div>
      </div>
        <div class="modal-footer">
          <button class="btn oren" style="width:50%;" name="simpan">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    function start(){
        var s = document.getElementById('start').value;
        location.href='?p=pembelian&s='+s;
    }
    function end(){
        var s = document.getElementById('start').value;
        var e = document.getElementById('end').value;
        location.href='?p=pembelian&s='+s+'&e='+e;
    }

    function modalHapus(id) {
      var myModal = new bootstrap.Modal(document.getElementById('modalHapus' + id));
      myModal.show();
    }
    function modalEdit(id) {
      var myModal = new bootstrap.Modal(document.getElementById('modalEdit' + id));
      myModal.show();
    }

    function rincian(id) {
        var myModal = new bootstrap.Modal(document.getElementById('rincian' + id));
        myModal.show();
    }

    let item=0;
    let total=0;
    function tambahbeli() {
        item+=1;
        let barang= document.getElementById("namabarang").value;
        let beratbarang= document.getElementById("beratbarang").value;
        let satuan= document.getElementById("satuan").value;
        let jumlah= document.getElementById("jumlahbeli").value;
        let harga= document.getElementById("hargabeli").value;
        let subtotal= harga*jumlah;

       
        total += subtotal;

        document.getElementById("jumlahbeli").value="";
        document.getElementById("hargabeli").value="";

        let data=document.getElementById("listitem").innerHTML;
        document.getElementById("listitem").innerHTML="<div class='row'><div class='col'>"+barang+"</div><div class='col'>"+beratbarang+" "+satuan+"</div><div class='col'>"+jumlah+"</div><div class='col'>"+harga+"</div><div class='col'>"+subtotal+"</div></div>"+data;
        document.getElementById("total").innerHTML=total;

        
        document.getElementById("phptotal").value=total;
        document.getElementById("phpitem").value=item;
        document.getElementById("phppembeli").value=document.getElementById("pembeli").value;
        document.getElementById("phpsupplier").value=document.getElementById("supplier").value;
        document.getElementById("phpfaktur").value=document.getElementById("faktur").value;

        
        document.getElementById("inputphp").innerHTML+="<input type='text' value='"+barang+"' name='namaitem"+item+"' hidden><input type='text' value='"+beratbarang+"' name='beratbarang"+item+"' hidden><input type='text' value='"+satuan+"' name='satuan"+item+"' hidden><input type='text' value='"+jumlah+"' name='jumlah"+item+"' hidden><input type='text' value='"+harga+"' name='harga"+item+"' hidden>";
    }


    function cari(){
        var kolom = document.getElementById('kolom').value;
        var cari = document.getElementById('cari').value;
        var s = document.getElementById('start').value;
        var e = document.getElementById('end').value;

        location.href='?p=pembelian&s='+s+'&e='+e+'&p=pembelian&kolom='+kolom+'&cari='+cari;
    }

    document.getElementById("cari").addEventListener("keypress",function(event){if(event.key==="Enter"){event.preventDefault(); document.getElementById("btncari").click();}});
</script>

<?php 
    
    //hapus data 
    if(isset($_POST['hapus'])){
      $kode = $_POST['kode'];

      $query = mysqli_query($conn, "DELETE FROM pembelian WHERE no_faktur = '$kode'");

        if($query){
          
            
          $query = mysqli_query($conn, "SELECT * FROM pembelian_detail WHERE no_faktur = '$kode'");
          while($data = mysqli_fetch_array($query)){
            $data2=mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE nama_barang = '".$data['nama_barang']."'"));
            $stok=$data2['stok']-$data['jumlah_beli'];
            $query2 = mysqli_query($conn, "UPDATE barang SET stok = '$stok' WHERE nama_barang = '".$data['nama_barang']."'");
          }

            $query = mysqli_query($conn, "DELETE FROM pembelian_detail WHERE no_faktur = '$kode'");
            echo "<script>alert('Barang berhasil dihapus'); location.href='?p=pembelian'; </script>";
        }else{
            echo "<script>alert('Barang gagal dihapus')</script>";
        }
    }
?>
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    input[type=number] {
    -moz-appearance: textfield;
    }
</style>