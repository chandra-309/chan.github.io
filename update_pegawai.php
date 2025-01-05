<?php
include 'function.php'; // Sambungkan ke database

if (isset($_POST['id_pegawai']) && isset($_POST['nama']) && isset($_POST['jabatan'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];

    // Query untuk memperbarui data pegawai
    $updateQuery = "UPDATE pegawai SET nama = '$nama', jabatan = '$jabatan' WHERE id_pegawai = '$id_pegawai'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Data pegawai berhasil diperbarui.'); window.location.href='pegawai.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Data tidak lengkap.";
}
?>
