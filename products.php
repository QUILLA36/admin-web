<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "batik_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product data
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Fetch category data
$category_sql = "SELECT * FROM categories";
$category_result = $conn->query($category_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Produk Page</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/templatemo-style.css">
</head>

<body id="reportsPage">
<nav class="navbar navbar-expand-xl">
      <div class="container h-100">
        <a class="navbar-brand" href="index.html">
          <h1 class="tm-site-title mb-0">Produk Admin</h1>
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
                aria-expanded="false">
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
              <a class="nav-link active" href="products.html">
                <i class="fas fa-shopping-cart"></i> Produk
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="accounts.html">
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
                aria-expanded="false">
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
        <div class="row tm-content-row">
            <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 tm-block-col">
                <div class="tm-bg-primary-dark tm-block tm-block-products">
                    <div class="tm-product-table-container">
                        <table class="table table-hover tm-table-small tm-product-table">
                            <thead>
                                <tr>
                                    <th scope="col">NAMA PRODUK</th>
                                    <th scope="col">UNIT TERJUAL</th>
                                    <th scope="col">IN STOCK</th>
                                    <th scope="col">EXPIRE DATE</th>
                                    <th scope="col">EDIT</th>
                                    <th scope="col">HAPUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) { 
                                        $units_sold = isset($row["units_sold"]) ? $row["units_sold"] : 0; ?>
                                        <tr>
                                            <td class="tm-product-name">
                                                <a href="edit-product.php?id=<?= $row['id'] ?>" class="text-white">
                                                    <?= htmlspecialchars($row["name"]) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($units_sold) ?></td>
                                            <td><?= htmlspecialchars($row["stock"]) ?></td>
                                            <td><?= htmlspecialchars($row["expire_date"]) ?></td>
                                            <td>
                                                <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            </td>
                                            <td>
                                                <a href="delete.php?id=<?= $row['id'] ?>" 
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No products found</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="add-product.html" class="btn btn-primary btn-block text-uppercase mb-3">Tambah produk baru</a>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 tm-block-col">
    <div class="tm-bg-primary-dark tm-block tm-block-product-categories">
        <h4 class="mt-5" style="color: white; font-size: 18px;">Kategori Produk</h4> 

        <!-- Tambahkan div pembungkus untuk membuat scroll -->
        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #444; padding: 5px;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($category_result->num_rows > 0) {
                        while ($row = $category_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>
                                <a href="delete-category.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php } 
                    } else { ?>
                        <tr><td colspan="2" class="text-center">Tidak ada kategori</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <h5 style="color: white; font-size: 16px;">Tambah Kategori Baru</h5>
        <form action="add-category.php" method="POST">
            <input type="text" name="category_name" class="form-control mb-2" placeholder="Nama kategori baru" required>
            <button type="submit" class="btn btn-primary">Tambah Kategori</button>
        </form>
    </div>
</div>
    <footer class="tm-footer row tm-mt-small">
        <div class="col-12 font-weight-light">
            <p class="text-center text-white mb-0 px-4 small">
                Copyright &copy; <b>2018</b> All rights reserved.
            </p>
        </div>
    </footer>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(function() {
            $(".tm-product-name").on("click", function() {
                window.location.href = "edit-product.html";
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>