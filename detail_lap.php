<?php
// Dummy data untuk detail produk (karena tidak ada di database)
$produk_detail = [
    "Batik Tulis Premium" => [
        "deskripsi" => "Batik tulis dengan motif khas daerah dan bahan berkualitas.",
        "harga_satuan" => "Rp 350.000",
        "stok" => "Tersedia"
    ],
    "Batik Cap Modern" => [
        "deskripsi" => "Batik cap dengan desain modern, cocok untuk berbagai acara.",
        "harga_satuan" => "Rp 150.000",
        "stok" => "Tersedia"
    ],
    "Batik Kombinasi" => [
        "deskripsi" => "Batik kombinasi antara cap dan tulis, menghasilkan desain unik.",
        "harga_satuan" => "Rp 250.000",
        "stok" => "Pre-Order"
    ]
];

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "batik_store";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari laporan_harian berdasarkan ID
$sql = "SELECT * FROM laporan_harian WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Data tidak ditemukan.");
}

$produk = $row['produk']; // Nama produk dari database

// Ambil detail produk dari array, jika tidak ditemukan gunakan default
$detail = $produk_detail[$produk] ?? [
    "deskripsi" => "Deskripsi tidak tersedia.",
    "harga_satuan" => "-",
    "stok" => "-"
];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Weekly Report - Dashboard Admin Template</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link rel="stylesheet" href="jquery-ui-datepicker/jquery-ui.min.css" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/templatemo-style.css">
    <style>
      .nav-item.active .nav-link {
        background-color: #FFA500;
        color: white;
      }
    </style>
  </head>

  <body>
    <nav class="navbar navbar-expand-xl">
      <div class="container h-100">
        <a class="navbar-brand" href="index.html">
          <h1 class="tm-site-title mb-0">Produk Admin</h1>
        </a>
        <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
          <i class="fas fa-bars tm-nav-icon"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mx-auto h-100">
            <li class="nav-item">
              <a class="nav-link" href="index.html">
                <i class="fas fa-tachometer-alt"></i> Dashboard
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item dropdown active">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                <i class="far fa-file-alt"></i>
                <span> Laporan <i class="fas fa-angle-down"></i> </span>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="lap_harian.php">Laporan Harian</a>
                <a class="dropdown-item" href="lap_mingguan.php">Laporan Mingguan</a>
                <a class="dropdown-item" href="lap_tahunan.php">Laporan Tahunan</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="products.php">
                <i class="fas fa-shopping-cart"></i> Produk
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="accounts.html">
                <i class="far fa-user"></i> Akun
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                <i class="fas fa-cog"></i>
                <span> Setting <i class="fas fa-angle-down"></i> </span>
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Profile</a>
                <a class="dropdown-item" href="#">Billing</a>
                <a class="dropdown-item" href="#">Customize</a>
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
        <h2 class="mb-4" style="color: white">Detail Laporan Penjualan</h2>
        <table class="table table-bordered">
            <tr>
                <th>Produk</th>
                <td><?= htmlspecialchars($produk); ?></td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td><?= htmlspecialchars($row['tanggal']); ?></td>
            </tr>
            <tr>
                <th>Jumlah Terjual</th>
                <td><?= htmlspecialchars($row['jumlah_terjual']); ?></td>
            </tr>
            <tr>
                <th>Pendapatan</th>
                <td>Rp <?= htmlspecialchars($row['pendapatan']); ?></td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td><?= htmlspecialchars($detail['deskripsi']); ?></td>
            </tr>
            <tr>
                <th>Harga Satuan</th>
                <td><?= htmlspecialchars($detail['harga_satuan']); ?></td>
            </tr>
            <tr>
                <th>Stok</th>
                <td><?= htmlspecialchars($detail['stok']); ?></td>
            </tr>
        </table>
        <a href="lap_harian.php" class="btn btn-primary">Kembali</a>
    </div>
</body>
</html>
