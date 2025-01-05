<?php
include 'function.php'; // Sambungkan ke database

if (isset($_GET['id'])) {
    $id_pegawai = $_GET['id'];

    // Cek apakah ada penjualan yang terkait dengan pegawai ini
    $checkQuery = "SELECT COUNT(*) AS count FROM penjualan WHERE id_pegawai = '$id_pegawai'";
    $checkResult = mysqli_query($conn, $checkQuery);
    $checkRow = mysqli_fetch_assoc($checkResult);

    // Tampilan jika pegawai ada data penjualan terkait
    if ($checkRow['count'] > 0) {
        echo "<script>alert('Tidak dapat menghapus pegawai ini karena ada data penjualan yang terkait.'); window.location.href='pegawai.php';</script>";
    } else {
        // Query untuk menghapus pegawai
        $deleteQuery = "DELETE FROM pegawai WHERE id_pegawai = '$id_pegawai'";

        // tampilan jika berhasil menghapus data pegawai
        if (mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Data pegawai berhasil dihapus.'); window.location.href='pegawai.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    echo "ID pegawai tidak ditemukan.";
}
?>
