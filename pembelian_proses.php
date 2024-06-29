
<?php
    include('../../config/koneksi.php');
    include('../header.php');

    if(isset($_POST['simpan'])){
        date_default_timezone_set("Asia/Jakarta");
        $tanggal=date_format(date_create(date("Y/m/d")),"Y/m/d");
        $no=$_POST['faktur'];
        $n=$_POST['item'];
        $supplier=$_POST['supplier'];
        $pembeli=$_POST['pembeli'];
        $total=$_POST['total'];
        
        $query = mysqli_query($conn, "INSERT INTO pembelian(no_faktur, pembeli, supplier, tgl_pembelian, total_harga_pembelian) VALUES('$no', '$pembeli','$supplier', '$tanggal', '$total')");


        for($i=1;$i<=$n;$i++){
            
            $nama=$_POST['namaitem'.$i];
            $harga=$_POST['harga'.$i];
            $jumlah=$_POST['jumlah'.$i];
            $berat=$_POST['beratbarang'.$i];
            $satuan=$_POST['satuan'.$i];
            $query = mysqli_query($conn, "INSERT INTO pembelian_detail(no_faktur, nama_barang, harga_beli, jumlah_beli, berat_barang, satuan_berat) VALUES('$no','$nama', '$harga', '$jumlah', '$berat', '$satuan')");
            $data=mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE nama_barang = '$nama'"));
            
            if($data){
                $stok=$data['stok']+$jumlah;
                $query = mysqli_query($conn, "UPDATE barang SET stok = '$stok' WHERE nama_barang = '$nama'");
            }else{
                $query = mysqli_query($conn, "INSERT INTO barang(nama_barang, berat_barang, satuan_berat, stok, harga, terjual) VALUES('$nama', '$berat', '$satuan','$jumlah','$harga','0')");
            }
            
        }

        echo "<script>alert('Data berhasil ditambahkan'); history.back();</script>";
    }


    
    if(isset($_POST['edit'])){

        $kode = $_POST['kode'];
        date_default_timezone_set("Asia/Jakarta");
        $data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pembelian WHERE no_faktur = '$kode'"));
        $n=0;
        $tanggal=$data['tgl_pembelian'];

        $query = mysqli_query($conn, "DELETE FROM pembelian WHERE no_faktur = '$kode'");

        $query = mysqli_query($conn, "SELECT * FROM pembelian_detail WHERE no_faktur = '$kode'");
        while($data = mysqli_fetch_array($query)){
            $n+=1;
            $data2=mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE nama_barang = '".$data['nama_barang']."'"));
            $stok=$data2['stok']-$data['jumlah_beli'];
            $query2 = mysqli_query($conn, "UPDATE barang SET stok = '$stok' WHERE nama_barang = '".$data['nama_barang']."'");
        }

        $query = mysqli_query($conn, "DELETE FROM pembelian_detail WHERE no_faktur = '$kode'");

        $no=$_POST['kode'];
        $supplier=$_POST['editsupplier'];
        $pembeli=$_POST['editpembeli'];

        $total=0;
        
        for($i=1;$i<=$n;$i++){
            $tmp=0;
            $nama=$_POST['editnama'.$i];
            $harga=$_POST['editharga'.$i];
            $jumlah=$_POST['editjumlah'.$i];
            $berat=$_POST['editberatbarang'.$i];
            $satuan=$_POST['editsatuan'.$i];
            $total+=($harga*$jumlah);
            $query = mysqli_query($conn, "INSERT INTO pembelian_detail(no_faktur, nama_barang, harga_beli, jumlah_beli, berat_barang, satuan_berat) VALUES('$no','$nama', '$harga', '$jumlah', '$berat', '$satuan')");
            $data=mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE nama_barang = '$nama'"));
            
            $stok=$data['stok']+$jumlah;
            $query = mysqli_query($conn, "UPDATE barang SET stok = '$stok' WHERE nama_barang = '$nama'");
        }

        $query = mysqli_query($conn, "INSERT INTO pembelian(no_faktur, pembeli, supplier, tgl_pembelian, total_harga_pembelian) VALUES('$no', '$pembeli','$supplier', '$tanggal', '$total')");


        echo "<script>alert('Data berhasil diubah!'); history.back(); </script>";
    }
?>