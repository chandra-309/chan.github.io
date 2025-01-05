<?php
include 'function.php'; // Sambungkan ke database

if (isset($_POST['nota']) && isset($_POST['total'])) {
    $nota = $_POST['nota'];
    $total = $_POST['total'];

    // Query untuk memperbarui kolom total di tabel penjualan
    $updateTotal = mysqli_query($conn, "UPDATE penjualan SET total = '$total' WHERE nonota = '$nota'");

    if ($updateTotal) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
