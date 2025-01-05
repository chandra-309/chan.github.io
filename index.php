<?php

require 'ceklogin.php';


//hitung jumlah penjualan
$h1 = mysqli_query($conn, "SELECT * FROM penjualan");
$hp = mysqli_num_rows($h1); //jumlah penjualan

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Data Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Aplikasi Kasir AILGA.NET</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Order
                        </a>
                        <a class="nav-link" href="stok.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-balance-scale"></i></div>
                            Stok Barang
                        </a>
                        <a class="nav-link" href="pegawai.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-friends"></i></div>
                            Kelola Pegawai
                        </a>
                        <a class="nav-link" href="logout.php">
                            Logout
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Transaksi</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Selamat Datang di Aplikasi Kasir by Chandra</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Jumlah Transaksi: <?= $hp; ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-info mb-4" data-toggle="modal" data-target="#myModal">
                        Tambah Penjualan Baru
                    </button>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Penjualan
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No Nota</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah Belanja</th>
                                        <th>Total Bayar</th>
                                        <th>Nama Kasir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $get = mysqli_query($conn, "select * from penjualan p, pegawai pg where p.id_pegawai=pg.id_pegawai");

                                    while ($p = mysqli_fetch_array($get)) {
                                        $no_nota = $p['nonota'];
                                        $tanggal_penjualan = $p['tanggal'];
                                        $total = $p['total'];
                                        $namapegawai = $p['nama'];

                                        //hitung jumlah
                                        $hitungjumlah = mysqli_query($conn, "SELECT * FROM detailpenjualan WHERE nonota='$no_nota'");
                                        $jumlahbelanja = mysqli_num_rows($hitungjumlah);

                                        // Hitung total bayar (penjumlahan subtotal di detailpenjualan)
                                        $hitungtotal = mysqli_query($conn, "SELECT SUM(subtotal) AS total_bayar FROM detailpenjualan WHERE nonota = '$no_nota'");
                                        $total_bayar = mysqli_fetch_assoc($hitungtotal)['total_bayar'];

                                        // Cek jika $total_bayar tidak kosong atau null, beri nilai default 0 jika kosong
                                        if (empty($total_bayar)) {
                                            $total_bayar = 0;
                                        }

                                        // Update kolom total di tabel penjualan
                                        $updateTotal = mysqli_query($conn, "UPDATE penjualan SET total = '$total_bayar' WHERE nonota = '$no_nota'");

                                        if (!$updateTotal) {
                                            echo "Error updating total: " . mysqli_error($conn);
                                        }

                                        ?>
                                        <tr>
                                            <td><?= $no_nota; ?></td>
                                            <td><?= $tanggal_penjualan; ?></td>
                                            <td><?= $jumlahbelanja; ?></td>
                                            <td>Rp <?= number_format($total_bayar); ?></td>
                                            <td><?= $namapegawai; ?></td>
                                            <td>
                                                <a href="transaksi.php?nota=<?= $no_nota; ?>" class="btn btn-primary"
                                                    target="blank">Transaksi</a>
                                                <a href="delete.php" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Chandra Website 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>










<?php
if (isset($_SESSION['delete_success'])) {
    echo '<script>
        $(document).ready(function(){
            $("#deleteSuccessModal").modal("show");
        });
    </script>';
    unset($_SESSION['delete_success']); // Bersihkan variabel sesi setelah ditampilkan
}
?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'function.php'; // Koneksi database

if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];

    // Hapus data dari tabel detailpenjualan terlebih dahulu
    $delete_detail = mysqli_query($conn, "DELETE FROM detailpenjualan WHERE nonota='$id_produk'");
    if (!$delete_detail) {
        die('Error deleting detail transaction: ' . mysqli_error($conn));
    }

    // Setelah detail dihapus, hapus data transaksi dari tabel penjualan
    $delete_transaksi = mysqli_query($conn, "DELETE FROM penjualan WHERE nonota='$id_produk'");
    if (!$delete_transaksi) {
        die('Error deleting transaction: ' . mysqli_error($conn));
    }

    // Set pesan sukses di session
    $_SESSION['delete_success'] = 'Transaksi berhasil dihapus.';
    
    // Redirect ke stok.php setelah berhasil menghapus
    header("Location: index.php");
    exit; // Pastikan script berhenti setelah redirection
} else {
    echo 'Invalid request.';
}
?>










<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Penjualan Baru</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post">

                <!-- Modal body -->
                <div class="modal-body">
                    Pilih Kasir
                    <select name="idpegawai" class="form-control">

                        <?php
                        $getpegawai = mysqli_query($conn, "select * from pegawai");

                        while ($pg = mysqli_fetch_array($getpegawai)) {
                            $idpegawai = $pg['id_pegawai'];
                            $nama_pegawai = $pg['nama'];
                            $jabatan = $pg['jabatan'];

                            ?>

                            <option value="<?= $idpegawai; ?>"><?= $nama_pegawai; ?> - <?= $jabatan; ?></option>

                            <?php
                        }
                        ;
                        ?>

                    </select>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="nambahpenjualan">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </form>

        </div>
    </div>
</div>

</html>


