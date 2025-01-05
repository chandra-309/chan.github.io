<?php

// Periksa apakah sesi sudah dimulai sebelum memanggil session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Pengenalan server
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "penjualan";

// Buat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function untuk login
if(isset($_POST['login'])){
    // Inisiasi variabel
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($conn, "SELECT * FROM pegawai WHERE username='$username' AND password='$password' ");
    $hitung = mysqli_num_rows($check);

    // Validasi login
    if($hitung > 0){
        // Jika data berhasil ditemukan
        $_SESSION['login'] = 'True';

        // Dan berhasil login
        header('location:index.php');
    } else {
        // Jika Data tidak ditemukan dan Gagal Login
        echo '
        <script>alert("Username atau Password Salah");
        window.location.href="login.php"
        </script>
        ';
    }
}

// Function untuk Tambah Barang
if(isset($_POST['nambahbarang'])){
    $nama_produk = $_POST['nama_produk'];
    $hrg_beli = $_POST['hrg_beli'];
    $hrg_jual = $_POST['hrg_jual'];
    $stock = $_POST['stock'];

    $insert = mysqli_query($conn, "insert into tblbarang (nama_produk,hrg_beli,hrg_jual,stock) 
              values ('$nama_produk', '$hrg_beli', '$hrg_jual', '$stock')");
    
    if($insert){
        header('location:stok.php');
    } else {
        echo '
        <script>alert("Gagal tambah barang");
        window.location.href="stok.php"
        </script>
        ';
    }
}

// Function untuk Tambah Pegawai
if(isset($_POST['nambahpegawai'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];

    $insert = mysqli_query($conn, "insert into pegawai (username,password,nama,jabatan) 
              values ('$username', '$password', '$nama', '$jabatan')");
    
    if($insert){
        header('location:pegawai.php');
    } else {
        echo '
        <script>alert("Gagal tambah pegawai");
        window.location.href="pegawai.php"
        </script>
        ';
    }
}

// Function untuk Tambah Penjualan
if(isset($_POST['nambahpenjualan'])){
    $id_pegawai = $_POST['idpegawai'];
    
    $insert = mysqli_query($conn, "insert into penjualan (id_pegawai) 
              values ('$id_pegawai')");
    
    if($insert){
        header('location:index.php');
    } else {
        echo '
        <script>alert("Gagal tambah penjualan");
        window.location.href="index.php"
        </script>
        ';
    }
}

// Function untuk Tambah Transaksi
if(isset($_POST['addjualan'])){
    $id_produk = $_POST['idproduk'];//id produk/barang
    $nota = $_POST['nota'];//no nota penjualan
    $qty = $_POST['qty'];//jumlah barang yang mau dikeluarkan
    
    // Query untuk mendapatkan harga jual berdasarkan id_produk
    $result = mysqli_query($conn, "SELECT hrg_jual FROM tblbarang WHERE id_produk = '$id_produk'");
    $row = mysqli_fetch_assoc($result);
    $harga = $row['hrg_jual'];

    // Hitung stock barang saat ini
    $hitung1 = mysqli_query($conn, "SELECT * FROM tblbarang WHERE id_produk='$id_produk'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stocksaatini = $hitung2['stock']; // Stock barang saat ini


    if($stocksaatini >= $qty){
        // Kurangi Stock barang dengan jumlah barang yang akan dikeluarkan
        $selisih = $stocksaatini-$qty;
        
        // Stock barang mencukupi
        $insert = mysqli_query($conn, "INSERT INTO detailpenjualan (nonota,id_produk,harga,qty) VALUES ('$nota','$id_produk', '$harga', '$qty')");
        $update = mysqli_query($conn, "UPDATE tblbarang SET stock='$selisih' WHERE id_produk='$id_produk'");
        

        if($insert&&$update){
            header('location:transaksi.php?nota='.$nota);
        } else {
            echo '
            <script>alert("Gagal tambah jualan");
            window.location.href="transaksi.php?nota='.$nota.'"
            </script>
            ';
        }
    } else {
        // Stock tidak cukup
        echo '
        <script>alert("Stock Barang tidak mencukupi");
        window.location.href="transaksi.php?nota='.$nota.'"
        </script>
        ';
    }
    
   
}

?>