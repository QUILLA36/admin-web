<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'batik_store';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil filter role jika ada
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';

// Query ambil data berdasarkan role yang dipilih
$sql = "SELECT id, username, email, role, phone FROM users";
if ($role_filter && in_array($role_filter, ['admin', 'user'])) {
    $sql .= " WHERE role = ?";
}

$sql .= " ORDER BY id DESC";
$stmt = $conn->prepare($sql);

// Bind parameter jika ada filter
if ($role_filter && in_array($role_filter, ['admin', 'user'])) {
    $stmt->bind_param("s", $role_filter);
}

$stmt->execute();
$result = $stmt->get_result();
$total_users = $result->num_rows;
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
                <a class="nav-link active" href="accounts.php">
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
        <h2 class="mb-4" style="color: white;">Manajemen Akun Pengguna</h2>

        <!-- Filter Role -->
        <form method="GET" class="mb-3">
            <label for="role" style="color: white;">Filter berdasarkan peran:</label>
            <select name="role" id="role" class="custom-select w-25 d-inline" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="admin" <?= $role_filter == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="user" <?= $role_filter == 'user' ? 'selected' : '' ?>>User</option>
            </select>
        </form>

        <div style="max-height: 300px; overflow-y: auto;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Email</th>
                <th>Peran</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = $total_users; ?>
            <?php if ($total_users > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no-- ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pengguna.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
        <a href="add_user.php" class="btn btn-success mt-4">Tambah Pengguna</a>
    </div>
    <footer class="tm-footer row tm-mt-small">
        <div class="col-12 font-weight-light">
          <p class="text-center text-white mb-0 px-4 small">
            Copyright &copy; <b>2018</b> All rights reserved. 
            
          </p>
        </div>
      </footer>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- https://jquery.com/download/ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
</body>
</html>
<?php $conn->close(); ?>
