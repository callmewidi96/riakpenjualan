<h1 class="h2 mb-3">Data Barang</h1>
<button type="button" class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Data</button>

<div class="d-flex">
  <select name="kolom" class="form-select w-25" id="kolom" value="username">
      <option value="kode_barang" id="opsikode_barang">Kode</option>
      <option value="nama_barang" id="opsinama_barang">Nama Barang</option>
      <option value="kategori" id="opsikategori">Kategori</option>
  </select>
  
  <input type="text" class="form-control w-100" placeholder="Cari barang..." id="cari" autocomplete="off">
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" onclick="cari()" id="btncari">Cari</button>
  </div>
</div>
<br>
<table class="table table-hover table-bordered">
  <thead class="thead">
    <tr>
        <th width="10%">Kode</th>
        <th>Nama Barang</th>
        <th>Berat</th>
        <th>Gambar</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Harga</th>
        <th>Terjual</th>
        <th>Rating</th>
        <th>Deskripsi</th>
        <th width="20%">Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    include('../config/koneksi.php');

    $kolom="";
    if (isset($_GET["kolom"])){
      $kolom=$_GET["kolom"];
      $cari=$_GET["cari"];
      $query = mysqli_query($conn, "SELECT * FROM barang WHERE $kolom LIKE '$cari%'");
  ?>
    <script>
      document.getElementById(<?php echo "'opsi".$kolom."'";?>).selected=true;
      document.getElementById("cari").value=<?php echo "'$cari'";?>;
    </script>
  <?php
    }else{
      $query = mysqli_query($conn, "SELECT * FROM barang");
    }
    while($data = mysqli_fetch_array($query)){
  ?>

    <tr>
        <td><?php echo $data['kode_barang']; ?></td>
        <td><?php echo $data['nama_barang']; ?></td>
        <td><?php echo $data['berat_barang']; ?> <?php echo $data['satuan_berat']; ?> </td>
        <td><img src="gambar\<?php echo $data['gambar']; ?>" style="max-height:100px;max-width:100px"></td>
        <td><?php echo $data['kategori']; ?></td>
        <td><?php echo $data['stok']; ?></td>
        <td><?php echo number_format($data['harga'],2,',','.'); ?></td>
        <td><?php echo $data['terjual']; ?></td>
        <?php
          $query2 = mysqli_query($conn, "SELECT * FROM ulasan WHERE kode_barang = '".$data['kode_barang']."'");
          $baris=mysqli_num_rows($query2);  
          $bintang=0; 
          while($data2 = mysqli_fetch_array($query2)){$bintang+=$data2['rating'];}  
          if($baris!=0){
              $rating=$bintang/$baris;
          }else{
              $rating=0;
          }       
        ?>
        <td><?php echo round($rating, 1); ?></td>
        
        <td><?php echo $data['deskripsi']; ?></td>
        <td>
            <button type="button" class="btn btn-outline-info btn-sm" 
                onclick="modalEdit('<?php echo $data['kode_barang']; ?>')">Edit
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="modalHapus(<?php echo $data['kode_barang']; ?>)">Hapus
            </button>
        </td>
    </tr>
    
      <!-- modal edit barang -->
      <div class="modal fade" id="modalEdit<?php echo $data['kode_barang']; ?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Data <?php echo $data['nama_barang']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
              <div class="modal-body">

                <input type="hidden" name="kode" value="<?php echo $data['kode_barang']; ?>">
                <input type="hidden" name="gambarlama" value="<?php echo $data['gambar']; ?>">

                <label for="nama" class="form-label">Gambar</label>
                <input type="file" class="form-control" name="gambar" accept="image/*" style="border:1px solid #bebebe">

                <label for="nama<?php echo $data['kode_barang']; ?>" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="nama<?php echo $data['kode_barang']; ?>" name="editnama" value="<?php echo $data['nama_barang']; ?>">
              
                <label for="nama" class="form-label">Berat Barang</label>
                <div class="input-group">
                  <input type="number" class="form-control w-50" id="beratbarang<?php echo $data['kode_barang']; ?>" name="editberatbarang" value="<?php echo $data['berat_barang']; ?>"> 
                  <select name="editsatuan"  class="form-select"  id="satuan<?php echo $data['kode_barang']; ?>" value="<?php echo $data['satuan_berat']; ?>">
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
                <label for="stok<?php echo $data['kode_barang']; ?>" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok<?php echo $data['kode_barang']; ?>" name="editstok" value="<?php echo $data['stok']; ?>">
                
                <label for="stok" class="form-label">Kategori</label>
                <select name="editkategori" class="form-select" id="editkategori">
                  <?php
                    $query2 = mysqli_query($conn, "SELECT * FROM kategori");
                    while($data2 = mysqli_fetch_array($query2)){
                      if($data2['nama_kategori']==$data['kategori']){
                  ?>
                    <option value="<?php echo $data2['nama_kategori']; ?>" selected><?php echo $data2['nama_kategori']; ?></option>
                  <?php 
                      }else{
                  ?>
                    <option value="<?php echo $data2['nama_kategori']; ?>"><?php echo $data2['nama_kategori']; ?></option>
                  <?php 
                      }
                    }
                  ?>
                </select>
                  
                <label for="editdeskripsi" class="form-label">Deskripsi</label>
                <textarea type="text" class="form-control" name="editdeskripsi"></textarea>

                <label for="harga<?php echo $data['kode_barang']; ?>" class="form-label">Harga</label>
                
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" class="form-control" id="editharga<?php echo $data['kode_barang']; ?>" name="editharga" 
                    value="<?php echo $data['harga']; ?>">
                  <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                  </div>
                </div>
              
              </div>
              <div class="modal-footer">
                <button class="btn oren" style="width:50%;">Simpan</button>
              </div>
              </form>
            </div>
          </div>
        </div>
    
        <!-- modal hapus barang -->
        <div class="modal fade" id="modalHapus<?php echo $data['kode_barang']; ?>" 
          tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Hapus <?php echo $data['nama_barang']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
              <div class="modal-body">
                <label class="form-label">Gambar</label><br>
                <img class="img-thumbnail" src="gambar\<?php echo $data['gambar']; ?>" width=""><br>
                <input type="hidden" name="kode" value="<?php echo $data['kode_barang']; ?>">

                <label for="hapusnama<?php echo $data['kode_barang']; ?>" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="hapusnama<?php echo $data['kode_barang']; ?>" name="hapusnama" 
                  value="<?php echo $data['nama_barang']; ?>" readonly>
                  
                <label for="hapusstok<?php echo $data['kode_barang']; ?>" class="form-label">Stok</label>
                <input type="text" class="form-control" id="hapusstok<?php echo $data['kode_barang']; ?>" name="hapusstok" 
                  value="<?php echo $data['stok']; ?>" readonly>

                <label for="hapuskategori<?php echo $data['kode_barang']; ?>" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="hapusstok<?php echo $data['kode_barang']; ?>" name="hapuskategori" 
                  value="<?php echo $data['kategori']; ?>" readonly>
                  
                <label for="hapusdeskripsi" class="form-label">Deskripsi</label>
                <textarea type="text" class="form-control" name="hapusdeskripsi" readonly><?php echo $data['deskripsi']; ?></textarea>

                <label for="hapusjual<?php echo $data['kode_barang']; ?>" class="form-label">Terjual</label>
                <input type="text" class="form-control" id="hapusjual<?php echo $data['kode_barang']; ?>" name="hapusjual" 
                  value="<?php echo $data['terjual']; ?>" readonly>

                <label for="hapusharga<?php echo $data['kode_barang']; ?>" class="form-label">Harga</label>

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="text" class="form-control" id="hapusharga<?php echo $data['kode_barang']; ?>" name="hapusharga" 
                    value="<?php echo $data['harga']; ?>" readonly>
                  <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button class="btn oren" style="width:50%;">Hapus</button>
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


<!-- modal tambah barang -->

<div class="modal fade" id="modalTambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
        <div class="modal-body">
          <label for="nama" class="form-label">Gambar</label>
          <input type="file" class="form-control" name="gambar" accept="image/*" style="border:1px solid #bebebe">
          
          <label for="nama" class="form-label">Nama Barang</label>
          <input type="text" class="form-control" id="nama" name="nama_barang">

          <label for="nama" class="form-label">Berat Barang</label>
          <div class="input-group">
            <input type="number" class="form-control w-50" id="beratbarang" name="tambahberatbarang"> 
            <select name="tambahsatuan"  class="form-select"  id="satuan" value="kg">
              <option value="kg" id="kg">kg</option>
              <option value="g" id="g">g</option>
              <option value="l" id="l">l</option>
              <option value="ml" id="ml">ml</option>
            </select>
          </div>
          
          <label for="stok" class="form-label">Stok</label>
          <input type="number" class="form-control" id="stok" name="stok">
          
          <label for="kategori" class="form-label">Kategori</label>
          <select name="tambahkategori" class="form-select" id="kategori" >

          <?php
            $query = mysqli_query($conn, "SELECT * FROM kategori");
            while($data = mysqli_fetch_array($query)){
          ?>
            <option value="<?php echo $data['nama_kategori']; ?>"><?php echo $data['nama_kategori']; ?></option>
          <?php 
            }
          ?>
          </select>

          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea type="text" class="form-control" name="deskripsi"></textarea>
          <label for="harga" class="form-label">Harga Jual</label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Rp.</span>
            </div>
            <input type="number" class="form-control" id="harga<?php echo $data['kode_barang']; ?>" name="tambahharga">
            <div class="input-group-append">
              <span class="input-group-text">.00</span>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn oren" style="width:50%;">Tambahkan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function modalEdit(id) {
    var myModal = new bootstrap.Modal(document.getElementById('modalEdit' + id));
    myModal.show();
  }

  function modalHapus(id) {
    var myModal = new bootstrap.Modal(document.getElementById('modalHapus' + id));
    myModal.show();
  }

  function cari(){
    var kolom = document.getElementById('kolom').value;
    var cari = document.getElementById('cari').value;

    location.href='?p=barang&kolom='+kolom+'&cari='+cari;
  }

  document.getElementById("cari").addEventListener("keypress",function(event){if(event.key==="Enter"){event.preventDefault(); document.getElementById("btncari").click();}});
</script>

<?php 
    //tambah data barang
    if(isset($_POST['nama_barang'])){
        $gambar = $_FILES["gambar"]["name"];
        $folder = "gambar/" . $gambar;
        $tmp = $_FILES["gambar"]["tmp_name"];
        move_uploaded_file($tmp, $folder);

        $nama = $_POST['nama_barang'];
        $berat = $_POST['tambahberatbarang'];
        $satuan = $_POST['tambahsatuan'];
        $kategori = $_POST['tambahkategori'];
        $harga = $_POST['tambahharga'];
        $stok = $_POST['stok'];
        $deskripsi = $_POST['deskripsi'];
        $query = mysqli_query($conn, "SELECT * FROM barang WHERE nama_barang = '$nama' AND kategori = '$kategori'");
        if(mysqli_num_rows($query)>0){
          echo "<script>alert('Data barang tersedia'); location.href='?p=barang'; </script>";
        }else{
          $query = mysqli_query($conn, "INSERT INTO barang(nama_barang, berat_barang, satuan_berat, gambar, kategori, deskripsi, stok, harga, terjual) VALUES('$nama', '$berat', '$satuan', '$gambar','$kategori','$deskripsi','$stok','$harga','0')");

          if($query){
              echo "<script>alert('Barang berhasil ditambah'); location.href='?p=barang'; </script>";
          }else{
              echo "<script>alert('Barang gagal ditambah')</script>";
          }
        }
    }

    //edit data barang
    if(isset($_POST['editnama'])){

      $gambar = $_FILES["gambar"]["name"];
      if($gambar!=""){
        $folder = "gambar/" . $gambar;
        $tmp = $_FILES["gambar"]["tmp_name"];
        move_uploaded_file($tmp, $folder);
      }else{
        $gambar = $_POST['gambarlama'];
      }

      $kode = $_POST['kode'];
      $nama = $_POST['editnama'];
      $berat = $_POST['editberatbarang'];
      $satuan = $_POST['editsatuan'];
      $stok = $_POST['editstok'];
      $kategori = $_POST['editkategori'];
      $harga = $_POST['editharga'];
      $deskripsi = $_POST['editdeskripsi'];

      $query = mysqli_query($conn, "UPDATE barang SET nama_barang = '$nama', berat_barang = '$berat', satuan_berat = '$satuan',  gambar = '$gambar', stok = '$stok', kategori = '$kategori', deskripsi = '$deskripsi', harga = '$harga' WHERE kode_barang = '$kode'");

        if($query){
            echo "<script>alert('Barang berhasil diedit'); location.href='?p=barang'; </script>";
        }else{
            echo "<script>alert('Barang gagal diedit')</script>";
        }
    }

    //hapus data barang
    if(isset($_POST['hapusnama'])){
      $kode = $_POST['kode'];
      unlink("test.txt");

      $query = mysqli_query($conn, "DELETE FROM barang WHERE kode_barang = '$kode'");

        if($query){
            echo "<script>alert('Barang berhasil dihapus'); location.href='?p=barang'; </script>";
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