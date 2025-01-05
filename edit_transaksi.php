<?php
include 'function.php'; // Koneksi database

if (isset($_POST['id_produk']) && isset($_POST['nota']) && isset($_POST['qty'])) {
    $id_produk = $_POST['id_produk'];
    $nota = $_POST['nota'];
    $new_qty = $_POST['qty'];

    // Dapatkan qty lama dan stok barang saat ini
    $result = mysqli_query($conn, "SELECT qty, stock FROM detailpenjualan dp JOIN tblbarang tb ON dp.id_produk=tb.id_produk 
                            WHERE dp.id_produk='$id_produk' AND dp.nonota='$nota'");
    $row = mysqli_fetch_assoc($result);
    $old_qty = $row['qty'];
    $stock = $row['stock'];

    // Hitung selisih qty
    $selisih_qty = $new_qty - $old_qty;

    // Cek jika jumlah baru melebihi stok, tampilkan pop up dengan pemberitahuan jumlah stok terbaru
    if ($selisih_qty > $stock) {
        echo "<script>
                alert('Error: Jumlah yang dimasukkan melebihi stok yang tersedia. Jumlah stok saat ini: $stock, ubah jumlah barang terlebih dahulu untuk melanjutkan');
                window.history.back();  // Kembali ke halaman sebelumnya
              </script>";
        exit(); // Hentikan eksekusi
    }

    // Hitung stok baru
    $new_stock = $stock - $selisih_qty;

    // Update stok di tblbarang
    $update_stock = mysqli_query($conn, "UPDATE tblbarang SET stock='$new_stock' WHERE id_produk='$id_produk'");

    // Update qty dan subtotal di detailpenjualan
    $harga = mysqli_query($conn, "SELECT hrg_jual FROM tblbarang WHERE id_produk='$id_produk'");
    $harga_barang = mysqli_fetch_assoc($harga)['hrg_jual'];
    $subtotal = $new_qty * $harga_barang;
    $update_transaksi = mysqli_query($conn, "UPDATE detailpenjualan SET qty='$new_qty', subtotal='$subtotal' 
                                    WHERE id_produk='$id_produk' AND nonota='$nota'");

    if ($update_stock && $update_transaksi) {
        header("Location: transaksi.php?nota=$nota");
    } else {
        echo 'Error updating transaction: ' . mysqli_error($conn);
    }
}
?>
