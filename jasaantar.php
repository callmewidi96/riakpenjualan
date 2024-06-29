<h1 class="h2 mb-3">Jasa Pengantaran</h1>
<div class="d-flex">
  <select name="kolom" class="form-select w-25" id="kolom" value="no">
      <option value="no" id="opsino">Nomor</option>
      <option value="nama_jasa_antar" id="opsinama_jasa_antar">Nama Jasa Pengantaran</option>
  </select>
  
  <input type="text" class="form-control w-100" placeholder="Cari jasa pengantaran..." id="cari" autocomplete="off">
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" onclick="cari()" id="btncari">Cari</button>
  </div>
</div><br>

<table class="table table-hover table-bordered ">
  <thead class="thead">
    <tr>
        <th width="10%">Nomor</th>
        <th>Nama Jasa Pengantaran</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    include('../config/koneksi.php');

    $kolom="";
    if (isset($_GET["kolom"])){
      $kolom=$_GET["kolom"];
      $cari=$_GET["cari"];
      $query = mysqli_query($conn, "SELECT * FROM jasa_antar WHERE $kolom LIKE '$cari%'");
  ?>
    <script>
      document.getElementById(<?php echo "'opsi".$kolom."'";?>).selected=true;
      document.getElementById("cari").value=<?php echo "'$cari'";?>;
    </script>
  <?php
    }else{
      $query = mysqli_query($conn, "SELECT * FROM jasa_antar");
    }
    while($data = mysqli_fetch_array($query)){
  ?>

    <tr>
        <td><?php echo $data['no']; ?></td>
        <td><?php echo $data['nama_jasa_antar']; ?></td>

    </tr>

    <?php 
        }
    ?>
  </tbody>
</table>


<script>

  function cari(){
    var kolom = document.getElementById('kolom').value;
    var cari = document.getElementById('cari').value;

    location.href='?p=jasaantar&kolom='+kolom+'&cari='+cari;
  }

  document.getElementById("cari").addEventListener("keypress",function(event){if(event.key==="Enter"){event.preventDefault(); document.getElementById("btncari").click();}});
</script>