<?php

require 'ceklogin.php';

//Hitung jumlah Item Barang
$hs = mysqli_query($conn, "SELECT * FROM tblbarang");
$hb = mysqli_num_rows($hs); //jumlah Item barang
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Stok Barang</title>
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
                        <a class="nav-link" href="stok.php">
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
                    <h1 class="mt-4">Stok Barang</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Selamat Datang di Pemantauan Stok Barang</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Jumlah Jenis Barang: <?=$hb;?></div>
                            </div>
                        </div>
                    </div>
                    <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-info mb-4" data-toggle="modal" data-target="#myModal">
                        Tambah Data Barang
                    </button>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Barang
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Harga Kulakan</th>
                                        <th>Harga Jual</th>
                                        <th>Stock Barang</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $get = mysqli_query($conn, "select * from tblbarang");
                                    $i = 1;

                                    while($b = mysqli_fetch_array($get)){
                                        $namaproduk = $b['nama_produk'];
                                        $harga_kulakan = $b['hrg_beli'];
                                        $harga_jual = $b['hrg_jual'];
                                        $stok = $b['stock'];                                 

                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$namaproduk;?></td>
                                        <td><?=$harga_kulakan;?></td>
                                        <td><?=$harga_jual;?></td>
                                        <td><?=$stok;?></td>
                                        <td>
    <!-- Tombol Edit -->
    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?= $b['id_produk']; ?>">Edit</button>

    <!-- Tombol Delete -->
    <a href="delete_barang.php?id=<?= $b['id_produk']; ?>"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Yakin mau hapus barang ini?')">Delete</a>
</td>
                                            </td>
                                    </tr>
                                   <!-- Modal Edit barang -->
<div class="modal fade" id="editModal<?= $b['id_produk']; ?>" tabindex="-1" role="dialog"
     aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="edit_barang.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_produk" value="<?= $b['id_produk']; ?>">


                    <!-- pop up edit barang (Nama Produk)-->
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama" class="form-control" value="<?= $b['nama_produk']; ?>" required>
                    </div>
                  

                    <!-- pop up edit barang (stock)-->
                    <div class="form-group"> 
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" class="form-control" value="<?= $b['stock']; ?>" required>
                    </div>

                    <!-- pop up edit barang (harga jual)-->
                    <div class="form-group">
                        <label for="hrg_jual">Harga Jual</label>
                        <input type="number" name="nama" class="form-control" value="<?= $b['hrg_jual']; ?>" required>
                    </div>
                </div>

                <!-- Tombol simpan edit barang-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                <h4 class="modal-title">Tambah Barang Baru</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post">

                <!-- Modal body -->
                <div class="modal-body">
                    <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk">
                    <input type="num" name="hrg_beli" class="form-control mt-2" placeholder="Harga Beli">
                    <input type="num" name="hrg_jual" class="form-control mt-2" placeholder="Harga Jual">
                    <input type="num" name="stock" class="form-control mt-2" placeholder="Stock Awal">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="nambahbarang">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </form>

        </div>
    </div>
</div>

</html>