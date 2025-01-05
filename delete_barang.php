<?php
include 'function.php'; // Sambungkan ke database

if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Query untuk mengecek apakah id_produk ada di tabel detailpenjualan / id produk tercatat di detailpenjualan apa tidak
    $checkQuery = "SELECT COUNT(*) as count FROM detailpenjualan WHERE id_produk = '$id_produk'";
    $result = mysqli_query($conn, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        // Jika ada, munculkan pop-up dan hentikan penghapusan
        echo "<script>alert('Data barang tidak dapat Dihapus. Karena ada transaksi terkait di detailpenjualan.'); window.location.href='stok.php';</script>";
    } else {
        // Jika tidak ada, lanjutkan penghapusan barang dari tblbarang
        $deleteQuery = "DELETE FROM tblbarang WHERE id_produk = '$id_produk'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Data barang berhasil dihapus, terimakasih atas perubahanya'); window.location.href='stok.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    echo "ID produk tidak ditemukan.";
}
?>
