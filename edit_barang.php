<?php
// Memastikan pengguna sudah login
include('ceklogin.php');

// Include koneksi ke database
include('function.php');

// Cek apakah id_produk dikirim melalui GET
if (!isset($_GET['id_produk'])) {
    echo "ID Produk tidak ditemukan.";
    exit;
}

$id_produk = $_GET['id_produk'];

// Ambil data produk berdasarkan id_produk
$query = "SELECT * FROM tblbarang WHERE id_produk = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Produk tidak ditemukan.";
    exit;
}

$data = $result->fetch_assoc();

// Pesan error jika ada masalah dengan form
$error_message = "";

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $hrg_jual = $_POST['hrg_jual'];
    $stock = $_POST['stock'];

    // Validasi data input
    if (empty($nama_produk) || empty($hrg_jual) || empty($stock)) {
        $error_message = "Semua bidang harus diisi.";
    } elseif ($hrg_jual <= 0 || $stock < 0) {
        $error_message = "Harga jual harus lebih besar dari 0 dan stok tidak boleh negatif.";
    } else {
        $query_update = "UPDATE tblbarang SET nama_produk = ?, hrg_jual = ?, stock = ? WHERE id_produk = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("siii", $nama_produk, $hrg_jual, $stock, $id_produk);

        if ($stmt_update->execute()) {
            header("Location: stok.php");
            exit;
        } else {
            $error_message = "Gagal mengupdate data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            width: 300px;
            margin: 0 auto;
        }
        label {
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        button {
            padding: 10px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Edit Barang</h1>

    <?php if ($error_message): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="nama_produk">Nama Produk:</label><br>
        <input type="text" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($data['nama_produk']); ?>" required><br><br>

        <label for="hrg_jual">Harga Jual:</label><br>
        <input type="number" id="hrg_jual" name="hrg_jual" value="<?php echo htmlspecialchars($data['hrg_jual']); ?>" required><br><br>

        <label for="stock">Stock:</label><br>
        <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($data['stock']); ?>" required><br><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
