<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'batik_store';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    // Validasi input
    if (!in_array($role, ['admin', 'user'])) {
        die("Peran tidak valid!");
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, role, phone, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $role, $phone, $password);

    if ($stmt->execute()) {
        header("Location: accounts.php");
        exit();
    } else {
        echo "Gagal menambahkan pengguna!";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Akun</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700"
    />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <!--
	Product Admin CSS Template
	https://templatemo.com/tm-524-product-admin
	-->
  </head>

  <body id="reportsPage">
    <div class="" id="home">
      <nav class="navbar navbar-expand-xl">
        <div class="container h-100">
          <a class="navbar-brand" href="index.html">
            <h1 class="tm-site-title mb-0">Admin Produk</h1>
          </a>
          <button
            class="navbar-toggler ml-auto mr-0"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <i class="fas fa-bars tm-nav-icon"></i>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto h-100">
              <li class="nav-item">
                <a class="nav-link" href="index.html">
                  <i class="fas fa-tachometer-alt"></i> Dashboard
                  <span class="sr-only">(Saat ini)</span>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="far fa-file-alt"></i>
                  <span> Laporan <i class="fas fa-angle-down"></i> </span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Laporan Harian</a>
                  <a class="dropdown-item" href="#">Laporan Mingguan</a>
                  <a class="dropdown-item" href="#">Laporan Tahunan</a>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="products.html">
                  <i class="fas fa-shopping-cart"></i> Produk
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link active" href="accounts.html">
                  <i class="far fa-user"></i> Akun
                </a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="fas fa-cog"></i>
                  <span> Setting <i class="fas fa-angle-down"></i> </span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Profil</a>
                  <a class="dropdown-item" href="#">Transaksi</a>
                </div>
              </li>
            </ul>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link d-block" href="login.html">
                  Admin, <b>Logout</b>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="container mt-5">
    <h2 style="color: white;">Tambah Pengguna</h2>
    <form method="POST">
        <div class="mb-3">
            <label style="color: white;">Username:</label>
            <input type="text" name="username" class="form-control" style="background-color: white; color: black; border: 1px solid #ccc;" required>
        </div>
        <div class="mb-3">
            <label style="color: white;">Email:</label>
            <input type="email" name="email" class="form-control" style="background-color: white; color: black; border: 1px solid #ccc;" required>
        </div>
        <div class="mb-3">
            <label style="color: white;">Password:</label>
            <input type="password" name="password" class="form-control" style="background-color: white; color: black; border: 1px solid #ccc;" required>
        </div>
        <div class="mb-3">
            <label style="color: white;">Peran:</label>
            <select name="role" class="form-control" 
                style="background-color: white; color: black; border: 1px solid #ccc; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" 
                required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="mb-3">
            <label style="color: white;">Telepon:</label>
            <input type="text" name="phone" class="form-control" style="background-color: white; color: black; border: 1px solid #ccc;" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="accounts.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
