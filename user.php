<h1 class="h2 mb-3">Data User</h1>
<button type="button" class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Admin</button>
  
<div class="d-flex">
  <select name="kolom" class="form-select w-25" id="kolom" value="username">
      <option value="username" id="opsiusername">Username</option>
      <option value="nohp" id="opsinohp">No.HP</option>
      <option value="alamat" id="opsialamat">Alamat</option>
  </select>
  
  <input type="text" class="form-control w-100" placeholder="Cari pengguna..." id="cari" autocomplete="off">
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" onclick="cari()" id="btncari">Cari</button>
  </div>
</div><br>

<table class="table table-hover table-bordered ">
  <thead class="thead">
    <tr>
        <th>Username</th>
        <th>Alamat</th>
        <th>No.HP</th>
        <th>Role</th>
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
      $query = mysqli_query($conn, "SELECT * FROM user WHERE $kolom LIKE '$cari%'");
  ?>
    <script>
      document.getElementById(<?php echo "'opsi".$kolom."'";?>).selected=true;
      document.getElementById("cari").value=<?php echo "'$cari'";?>;
    </script>
  <?php
    }else{
      $query = mysqli_query($conn, "SELECT * FROM user");
    }
    while($data = mysqli_fetch_array($query)){
  ?>

    <tr>
        <td><?php echo $data['username']; ?></td>
        <td><?php echo $data['alamat']; ?></td>
        <td><?php echo $data['nohp']; ?></td>
        <td><?php echo $data['role']; ?></td>
        <td>
            <?php 
              if($data['status']=="Aktif"){
            ?>
              <button type="button" class="btn btn-outline-success btn-sm"
                  onclick="modalblock('<?php echo $data['username']; ?>')">Aktif
              </button>

            <?php
              }else{
            ?>
                <button type="button" class="btn btn-outline-danger btn-sm"
                  onclick="modalblock('<?php echo $data['username']; ?>')">Block
              </button>
            <?php
              } 
            ?>
        </td>
    </tr>
        <!-- modal block user -->
        <div class="modal fade" id="modalblock<?php echo $data['username']; ?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Status <?php echo $data['username']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="#" method="POST">
              <div class="modal-body">
                <label for="block<?php echo $data['username']; ?>" class="form-label">Username</label>
                <input type="text" class="form-control" id="block<?php echo $data['username']; ?>" name="username" 
                  value="<?php echo $data['username']; ?>" readonly>
                
                <label for="status<?php echo $data['username']; ?>" class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="Aktif">Aktif</option>
                  <?php
                    if($data['status']=="Block"){
                  ?>
                    <option value="Block" selected>Block</option>      
                  <?php
                    }else{
                  ?>
                    <option value="Block" >Block</option>    
                  <?php
                    }
                  ?>
                </select>
              </div>
              <div class="modal-footer">
                <button class="btn oren" style="width:50%;">Ubah status</button>
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


<!-- modal tambah admin -->
<div class="modal fade" id="modalTambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" autocomplete="off">
        <div class="modal-body">
        <label for="tambahusername" class="form-label">Username</label>
          <input type="text" class="form-control" id="tambahusername" name="tambahusername">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password">
          <label for="password2" class="form-label">Konfirmasi Password</label>
          <input type="password" class="form-control" id="password2" name="password2">
        </div>

        <div class="modal-footer">
          <button class="btn oren" style="width:50%;" name="tambah">Tambahkan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function modalblock(username) {
    var myModal = new bootstrap.Modal(document.getElementById('modalblock' + username));
    myModal.show();
  }

  function cari(){
    var kolom = document.getElementById('kolom').value;
    var cari = document.getElementById('cari').value;

    location.href='?p=user&kolom='+kolom+'&cari='+cari;
  }

  document.getElementById("cari").addEventListener("keypress",function(event){if(event.key==="Enter"){event.preventDefault(); document.getElementById("btncari").click();}});
</script>

<?php 
    //ubah status user
    if(isset($_POST['username'])){
      $username = $_POST['username'];
      $status = $_POST['status'];

      $query = mysqli_query($conn, "UPDATE user SET status = '$status' WHERE username = '$username'");

        if($query){
            echo "<script>alert('Status user berhasil diubah'); location.href='?p=user'; </script>";
        }else{
            echo "<script>alert('Status user gagal diubah')</script>";
        }
    }

    if(isset($_POST['tambahusername'])){
      $username = $_POST['tambahusername'];

      if(empty($_POST['tambahusername'])||empty($_POST['password'])){
        echo "<script>alert('Masukkan semua data!');</script>";
      }else{
          if($_POST['password']==$_POST['password2']){
              $username=$_POST['tambahusername'];
              $pass=$_POST['password'];

              $query = "INSERT INTO user (`username`, `password`, `role`, `status`) VALUES ('$username', '$pass', 'adm', 'Aktif')";
              
              if (mysqli_query($conn, $query)) {
                echo "<script>location.href='';</script>";
              }else{
                  echo "<script>alert('Username sudah terdaftar!');</script>";
              }
              mysqli_close($conn);
          }else{
              echo "<script>alert('Password tidak sesuai!');</script>";
          }
      }
  }

?>