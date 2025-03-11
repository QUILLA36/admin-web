<?php
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database
$password = ""; // Sesuaikan jika ada password database
$dbname = "batik_store";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $expire_date = $_POST["expire_date"];
    $stock = $_POST["stock"];

    // Upload gambar
    $image = $_FILES["image"]["name"];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Simpan ke database
    $sql = "INSERT INTO products (name, description, category, expire_date, stock, image) 
            VALUES ('$name', '$description', '$category', '$expire_date', '$stock', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "Produk berhasil ditambahkan!";
        header("Location: products.php"); // Redirect ke halaman products
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
