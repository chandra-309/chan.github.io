<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start();
}

include 'function.php'; // Koneksi database

// Pastikan koneksi database tersedia
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_GET['nonota'])) {
    // Ambil dan amankan input `nonota`
    $nonota = mysqli_real_escape_string($conn, $_GET['nonota']);

    // Cek apakah ada data di detailpenjualan untuk transaksi ini
    $check_detail = mysqli_query($conn, "SELECT * FROM detailpenjualan WHERE nonota='$nonota'");

    if (!$check_detail) {
        die('Error pada query cek detail: ' . mysqli_error($conn)); // Debugging jika query gagal
    }

    // Debugging: Lihat jumlah baris yang ditemukan
    // echo "Jumlah baris ditemukan: " . mysqli_num_rows($check_detail) . "<br>";

    if (mysqli_num_rows($check_detail) > 0) {
        // Jika ada data di detailpenjualan, munculkan pop-up peringatan
        echo "<script>
            alert('Data detail transaksi masih ada. Harap hapus data di detailpenjualan terlebih dahulu.');
            window.location.href = 'index.php'; // Redirect kembali ke halaman sebelumnya
        </script>";
        exit;
    }

    // Jika tidak ada data di detailpenjualan, hapus data transaksi dari tabel penjualan
    $delete_transaksi = mysqli_query($conn, "DELETE FROM penjualan WHERE nonota='$nonota'");
    if (!$delete_transaksi) {
        die('Error deleting transaction: ' . mysqli_error($conn)); // Debugging jika penghapusan gagal
    }

    // Debugging: Konfirmasi penghapusan
    // echo "Transaksi berhasil dihapus.<br>";

    // Set pesan sukses di session
    $_SESSION['delete_success'] = 'Transaksi berhasil dihapus.';

    // Redirect ke index.php setelah berhasil menghapus
    header("Location: index.php");
    exit;
} else {
    echo "<script>
        alert('Nomor Nota tidak ditemukan. Harap pilih transaksi yang valid.');
        window.location.href = 'index.php';
    </script>";
    exit;
}
?>
