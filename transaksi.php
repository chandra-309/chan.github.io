<?php

require 'ceklogin.php';


if (isset($_GET['nota'])) {
    $nota = $_GET['nota'];

    $ambilnamakasir = mysqli_query($conn, "SELECT * FROM penjualan p, pegawai pg WHERE p.id_pegawai=pg.id_pegawai AND p.nonota='$nota'");
    $nk = mysqli_fetch_array($ambilnamakasir);
    $namakasir = $nk['nama'];
} else {
    header('location:index.php');
}
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
                    <h4 class="mt-4">Data Penjualan : <?= $nota; ?></h4>
                    <h4 class="mt-4">Nama Kasir : <?= $namakasir; ?></h4>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Selamat Datang di Aplikasi Kasir By Chandra</li>
                    </ol>

                    <div class="d-flex justify-content-between">
                        <!-- Button to Open the Modal -->
                        <button type="button" class="btn btn-info mb-4" data-toggle="modal" data-target="#myModal">
                            Tambah Transaksi
                        </button>

                        <!-- Menampilkan Total yang Harus Dibayar -->
                        <h4 class="text-right">
                            Total yang harus dibayar:
                            <span id="totalBayar">
                                Rp <?= number_format($totalbayar = 0); ?>
                            </span>
                        </h4>

                    </div>

                    <!-- Input untuk Nominal yang Dibayarkan dan Tombol Proses -->
                    <div class="row mb-4">
                        <div class="col-9">
                            <label for="nominalBayar">Nominal yang Dibayarkan:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" id="nominalBayar" class="form-control" placeholder="Masukkan Nominal"
                                    required onkeyup="formatRupiah(this)">
                            </div>
                        </div>
                        <div class="col-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" onclick="prosesPembayaran()">Proses</button>
                        </div>
                    </div>

                    <!-- JavaScript untuk Format Rupiah dengan Koma -->
                    <script>
                        function formatRupiah(input) {
                            let angka = input.value.replace(/[^,\d]/g, ''); // Hanya angka yang diperbolehkan
                            angka = angka.replace(/,/g, ''); // Hapus semua koma yang sudah ada

                            // Tambahkan format ribuan dengan koma
                            let ribuan = angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            input.value = ribuan;
                        }

                        function getNumericValue(value) {
                            return parseInt(value.replace(/,/g, '')) || 0; // Menghilangkan koma agar kembali jadi angka asli
                        }
                    </script>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Transaksi
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No Nota</th>
                                        <th>Nama Produk</th>
                                        <th>Harga Satuan</th>
                                        <th>QTY</th>
                                        <th>Sub Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getjualan = mysqli_query($conn, "SELECT * FROM detailpenjualan dp, tblbarang tb 
                                                            WHERE dp.id_produk=tb.id_produk AND nonota ='$nota' ");

                                    $totalbayar = 0;

                                    while ($gp = mysqli_fetch_array($getjualan)) {
                                        $no_nota = $gp['nonota'];
                                        $namaproduk = $gp['nama_produk'];
                                        $hargasatuan = $gp['harga'];
                                        $qty = $gp['qty'];
                                        $subtotal = $qty * $hargasatuan;

                                        // Tambahkan subtotal ke total bayar
                                        $totalbayar += $subtotal;

                                        // Update subtotal di tabel detailpenjualan
                                        $updateSubtotal = mysqli_query($conn, "UPDATE detailpenjualan
                                                            SET subtotal = '$subtotal'
                                                            WHERE nonota = '$no_nota'
                                                            AND id_produk = '" . $gp['id_produk'] . "'");

                                        // Cek apakah update berhasil
                                        if (!$updateSubtotal) {
                                            echo 'Error melakukan update Subtotal' . mysqli_error($conn);
                                        }

                                        ?>
                                        <tr>
                                            <td><?= $no_nota; ?></td>
                                            <td><?= $namaproduk; ?></td>
                                            <td>Rp <?= number_format($hargasatuan); ?></td>
                                            <td><?= number_format($qty); ?></td>
                                            <td>Rp <?= number_format($subtotal); ?></td>
                                            <td>
                                                <!-- Tombol Edit     -->
                                                <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-target="#editModal<?= $gp['id_produk']; ?>">Edit</button>

                                                <!-- Tombol Delete -->
                                                <a href="delete.php?id=<?= $gp['id_produk']; ?>&nota=<?= $no_nota; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin mau hapus transaksi ini?')">Delete</a>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Transaksi -->
                                        <div class="modal fade" id="editModal<?= $gp['id_produk']; ?>" tabindex="-1"
                                            role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit Transaksi</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="edit_transaksi.php" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id_produk"
                                                                value="<?= $gp['id_produk']; ?>">
                                                            <input type="hidden" name="nota" value="<?= $no_nota; ?>">
                                                            <div class="form-group">
                                                                <label for="qty">Jumlah Barang (QTY)</label>
                                                                <input type="number" name="qty" class="form-control"
                                                                    value="<?= $gp['qty']; ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
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
                        </div>
                    </div>

                    <script>
                        document.getElementById('totalBayar').innerHTML = 'Rp <?= number_format($totalbayar); ?>';
                    </script>

                    <!-- Proses Pembayaran Script -->
                    <script>
                        function prosesPembayaran() {
                            var nominalBayar = getNumericValue(document.getElementById('nominalBayar').value); // Konversi ke angka
                            var totalBayar = <?= $totalbayar ?>;
                            var kembalian = nominalBayar - totalBayar;

                            if (nominalBayar == "" || nominalBayar < totalBayar) {
                                alert("Nominal yang dibayarkan tidak mencukupi.");
                                return;
                            }

                            // Langkah 1: Simpan totalBayar ke database pada tabel penjualan
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "update_totalbayar.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    console.log("Total bayar berhasil disimpan.");
                                }
                            };
                            xhr.send("nota=<?= $nota ?>&total=" + totalBayar);

                            // Langkah 2: Buat halaman pop-up untuk menampilkan detail transaksi dan kembalian
                            var w = window.open('', '', 'width=800, height=600');
                            w.document.write('<html><head><title>Detail Pembayaran</title>');
                            w.document.write('<link rel="stylesheet" href="path_to_css_file">'); // Tambahkan CSS jika perlu
                            w.document.write('</head><body>');
                            w.document.write('<h2>Nota Transaksi: <?= $nota; ?></h2>');
                            w.document.write('<h4>Nama Kasir: <?= $namakasir; ?></h4>');
                            w.document.write('<table border="1" cellpadding="10" cellspacing="0">');
                            w.document.write('<thead><tr><th>Nama Produk</th><th>Harga Satuan</th><th>QTY</th><th>Sub Total</th></tr></thead><tbody>');

                            <?php
                            // Ulangi transaksi untuk ditampilkan di pop-up
                            $getjualan = mysqli_query($conn, "SELECT * FROM detailpenjualan dp, tblbarang tb WHERE dp.id_produk=tb.id_produk AND nonota ='$nota' ");
                            while ($gp = mysqli_fetch_array($getjualan)) {
                                $namaproduk = $gp['nama_produk'];
                                $hargasatuan = $gp['harga'];
                                $qty = $gp['qty'];
                                $subtotal = $qty * $hargasatuan;
                                ?>
                                w.document.write('<tr><td><?= $namaproduk; ?></td><td>Rp <?= number_format($hargasatuan); ?></td><td><?= number_format($qty); ?>
                                </td><td>Rp <?= number_format($subtotal); ?></td></tr>');
                                <?php
                            }
                            ?>

                            w.document.write('</tbody></table>');
                            w.document.write('<h3>Total Bayar: Rp <?= number_format($totalbayar); ?></h3>');
                            w.document.write('<h3>Nominal yang Dibayarkan: Rp ' + nominalBayar.toLocaleString('id-ID') + '</h3>');
                            w.document.write('<h3>Kembalian: Rp ' + kembalian.toLocaleString('id-ID') + '</h3>');

                            // Tambahkan tombol untuk cetak struk
                            w.document.write('<button onclick="window.print()" class="btn btn-success">Cetak Struk</button>');
                            w.document.write('</body></html>');
                            w.document.close();

                            // Langkah 3: Redirect ke halaman index.php setelah transaksi selesai
                            w.onafterprint = function () {
                                window.location.href = 'index.php';
                            };
                        }
                    </script>

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

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post">

                <!-- Modal body -->
                <div class="modal-body">
                    Pilih Barang
                    <select name="idproduk" class="form-control">

                        <?php
                        $getbarang = mysqli_query($conn, "SELECT * FROM tblbarang WHERE id_produk NOT IN 
                                    (SELECT id_produk FROM detailpenjualan WHERE nonota='$nota')");

                        while ($pb = mysqli_fetch_array($getbarang)) {
                            $nama_barang = $pb['nama_produk'];
                            $stock_barang = $pb['stock'];
                            $harga = $pb['hrg_jual'];
                            $id_barang = $pb['id_produk'];

                            ?>

                            <option value="<?= $id_barang; ?>"><?= $nama_barang; ?> - <?= $harga; ?> (Stock:
                                <?= $stock_barang; ?>)
                            </option>

                            <?php
                        }
                        ;
                        ?>

                    </select>

                    <input type="number" name="qty" class="form-control mt-4" placeholder="Jumlah Beli" min="1"
                        required>
                    <input type="hidden" name="nota" value="<?= $nota; ?>">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="addjualan">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </form>

        </div>
    </div>
</div>

</html>