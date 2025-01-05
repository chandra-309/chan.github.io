<?php 
require 'function.php';

// Set durasi timeout (dalam detik)
$timeoutDuration = 60; // 1 menit

// Periksa apakah sesi sudah dimulai sebelum memanggil session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah pengguna sudah login
if (isset($_SESSION['login'])) {
    // sudah login, cek apakah sesi masih aktif
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutDuration) {
        // Jika sudah melewati batas waktu, logout
        session_unset(); // Menghapus semua sesi
        session_destroy(); // Menghancurkan sesi
        header('location:login.php'); // Arahkan ke halaman login
        exit();
    }
    // Jika masih aktif, perbarui waktu terakhir aktivitas
    $_SESSION['last_activity'] = time();
}

// Proses login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Tambahkan logika autentikasi di sini
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['login'] = true;
        $_SESSION['last_activity'] = time(); // Set waktu aktivitas terakhir
        header('location:index.php');
        exit();
    } else {
        $error = 'Username atau Password salah';
    }
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
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        /* CSS Tambahan untuk mempercantik halaman login */
        body.bg-dark {
            background-color: #f0f2f5 !important;
            color: #343a40;
        }

        .card {
            background-color: #ffffff;
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 1rem;
            border-bottom: 0;
            border-radius: 1rem 1rem 0 0;
        }

        .form-floating>.form-control, .form-floating>.form-select {
            padding: 1rem 1.5rem;
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
        }

        .form-floating>label {
            padding: 0.75rem 1.5rem;
            pointer-events: none;
            font-size: 1rem;
            color: #6c757d;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .text-center a {
            color: #007bff;
        }

        .text-center a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-text h1 {
            color: #007bff;
            font-weight: bold;
        }

        .welcome-text p {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .container {
            margin-top: 5rem;
        }
    </style>
</head>
<body class="bg-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                <div class="card-body">
                                    <div class="welcome-text">
                                        <h1>Welcome Back!</h1>
                                        <p>Senang bertemu dengan Anda kembali di Kasir AILGA.NET</p>
                                    </div>
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $error; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputUsername" name="username" type="text" placeholder="Masukkan Username" required />
                                            <label for="inputUsername">Username</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Masukkan Password" required />
                                            <label for="inputPassword">Password</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="#">Lupa Password?</a></div>
                                    <div class="small mt-2"><a href="#">Daftar Akun Baru</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>