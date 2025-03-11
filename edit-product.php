<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "batik_store";

// Buat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID produk dari URL
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    die("Produk tidak ditemukan! (ID tidak ada di URL)");
}

// Ambil data produk berdasarkan ID
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Jika produk tidak ditemukan
if (!$product) {
    die("Produk dengan ID $product_id tidak ditemukan di database!");
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $category = trim($_POST["category"]);
    $expire_date = trim($_POST["expire_date"]);
    $stock = intval($_POST["stock"]);

    // Cek apakah ada file yang diunggah
    if (!empty($_FILES["image"]["name"])) {
        $image = basename($_FILES["image"]["name"]);
        $target_dir = "uploads/";
        
        // Pastikan folder uploads ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<script>console.log('Gambar berhasil diunggah!');</script>";
        } else {
            die("Gagal mengunggah gambar!");
        }
    } else {
        $image = $product["image"]; // Gunakan gambar lama jika tidak diubah
    }

    // Update data produk
    $sql = "UPDATE products SET name=?, description=?, category=?, expire_date=?, stock=?, image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $name, $description, $category, $expire_date, $stock, $image, $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='products.php';</script>";
    } else {
        echo "Gagal memperbarui produk: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/templatemo-style.css">
</head>

<body>
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
                <a class="dropdown-item" href="lap_harian.html">Laporan Harian</a>
                <a class="dropdown-item" href="lap_mingguan.html">Laporan Mingguan</a>
                <a class="dropdown-item" href="lap_tahunan.html">Laporan Tahunan</a>
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
<div class="container mt-5" style="background-color: #567086; padding: 20px; border-radius: 10px;">
        <h2 class="text-center" style="color: white">Edit Produk</h2>
        <div class="card p-4" style="background-color: #486177; border: none; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px;">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($product['name']); ?>" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" rows="5" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="">Pilih kategori</option>
                        <option value="Atasan" <?php if ($product['category'] == 'Atasan') echo 'selected'; ?>>Atasan</option>
                        <option value="Rok" <?php if ($product['category'] == 'Rok') echo 'selected'; ?>>Rok</option>
                        <option value="Kemeja" <?php if ($product['category'] == 'Kemeja') echo 'selected'; ?>>Kemeja</option>
                        <option value="Celana" <?php if ($product['category'] == 'Celana') echo 'selected'; ?>>Celana</option>
                        <option value="Dress" <?php if ($product['category'] == 'Dress') echo 'selected'; ?>>Dress</option>
                    </select>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="expire_date">Tanggal Kedaluwarsa</label>
                        <input id="expire_date" name="expire_date" type="date" value="<?php echo htmlspecialchars($product['expire_date']); ?>" class="form-control" required />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="stock">Stok</label>
                        <input id="stock" name="stock" type="number" value="<?php echo htmlspecialchars($product['stock']); ?>" class="form-control" required />
                    </div>
                </div>
                <div class="form-group">
                    <label>Gambar Produk</label>
                    <div class="mb-2">
                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product image" class="img-thumbnail" width="200px">
                    </div>
                    <input type="file" name="image" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Perbarui Produk</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
