<h1 class="h2 mb-3">Kategori</h1>
<button type="button" class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Data</button>


<div class="d-flex">
  <select name="kolom" class="form-select w-25" id="kolom" value="username">
      <option value="kode_kategori" id="opsikode_kategori">Kode</option>
      <option value="nama_kategori" id="opsinama_kategori">Nama Kategori</option>
  </select>
  
  <input type="text" class="form-control w-100" placeholder="Cari kategori..." id="cari" autocomplete="off">
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" onclick="cari()" id="btncari">Cari</button>
  </div>
</div><br>

<table class="table table-hover table-bordered ">
  <thead class="thead">
    <tr>
        <th width="10%">Kode</th>
        <th>Nama Kategori</th>
        <th>Jumlah Barang</th>
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
      $query = mysqli_query($conn, "SELECT * FROM kategori WHERE $kolom LIKE '$cari%'");
  ?>
    <script>
      document.getElementById(<?php echo "'opsi".$kolom."'";?>).selected=true;
      document.getElementById("cari").value=<?php echo "'$cari'";?>;
    </script>
  <?php
    }else{
      $query = mysqli_query($conn, "SELECT * FROM kategori");
    }
    while($data = mysqli_fetch_array($query)){
  ?>

    <tr>
        <td><?php echo $data['kode_kategori']; ?></td>
        <td><?php echo $data['nama_kategori']; ?></td>
        <td class="w-25"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM barang WHERE kategori='".$data['nama_kategori']."'")); ?></td>
        <td>
            <button type="button" class="btn btn-outline-info btn-sm" 
                onclick="modalEdit('<?php echo $data['kode_kategori']; ?>')">Edit
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="modalHapus(<?php echo $data['kode_kategori']; ?>)">Hapus
            </button>
        </td>
    </tr>
    
      <!-- modal edit barang -->
      <div class="modal fade" id="modalEdit<?php echo $data['kode_kategori']; ?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit <?php echo $data['nama_kategori']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="#" method="POST">
              <div class="modal-body">
                <input type="hidden" name="kode" value="<?php echo $data['kode_kategori']; ?>">

                <label for="editkategori<?php echo $data['kode_kategori']; ?>" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="editkategori<?php echo $data['kode_kategori']; ?>" name="editkategori" autocomplete="off" value="<?php echo $data['nama_kategori']; ?>">
              
              </div>
              <div class="modal-footer">
                <button class="btn oren" style="width:50%;">Simpan</button>
              </div>
              </form>
            </div>
          </div>
        </div>
    
        <!-- modal hapus barang -->
        <div class="modal fade" id="modalHapus<?php echo $data['kode_kategori']; ?>" 
          tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Hapus <?php echo $data['nama_kategori']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="#" method="POST">
              <div class="modal-body">
                <input type="hidden" name="kode" value="<?php echo $data['kode_kategori']; ?>">

                <label for="hapuskategori<?php echo $data['kode_kategori']; ?>" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" id="hapuskategori<?php echo $data['kode_kategori']; ?>" name="hapuskategori" 
                  value="<?php echo $data['nama_kategori']; ?>" readonly>

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

<!-- modal tambah kategori -->
<div class="modal fade" id="modalTambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" autocomplete="off">
        <div class="modal-body">
          <label for="nama" class="form-label">Nama Kategori</label>
          <input type="text" class="form-control" id="nama" name="nama">
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

    location.href='?p=kategori&kolom='+kolom+'&cari='+cari;
  }

  document.getElementById("cari").addEventListener("keypress",function(event){if(event.key==="Enter"){event.preventDefault(); document.getElementById("btncari").click();}});
</script>

<?php 
    //tambah data Kategori
    if(isset($_POST['nama'])){
        $nama = $_POST['nama'];

        $query = mysqli_query($conn, "INSERT INTO kategori(nama_kategori) VALUES('$nama')");

        if($query){
            echo "<script>alert('Kategori berhasil ditambah'); location.href='?p=kategori'; </script>";
        }else{
            echo "<script>alert('Kategori gagal ditambah')</script>";
        }
    }

    //edit data Kategori
    if(isset($_POST['editkategori'])){
      $kode = $_POST['kode'];
      $nama = $_POST['editkategori'];

      $query = mysqli_query($conn, "UPDATE kategori SET nama_kategori = '$nama' WHERE kode_kategori = '$kode'");

        if($query){
            echo "<script>alert('Kategori berhasil diedit'); location.href='?p=kategori'; </script>";
        }else{
            echo "<script>alert('Kategori gagal diedit')</script>";
        }
    }

    //hapus data Kategori
    if(isset($_POST['hapuskategori'])){
      $kode = $_POST['kode'];

      $query = mysqli_query($conn, "DELETE FROM kategori WHERE kode_kategori = '$kode'");

        if($query){
            echo "<script>alert('Kategori berhasil dihapus'); location.href='?p=kategori'; </script>";
        }else{
            echo "<script>alert('Kategori gagal dihapus')</script>";
        }
    }
?>