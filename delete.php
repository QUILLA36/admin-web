<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "batik_store";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pastikan ID produk dikirim melalui URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID dalam bentuk angka

    // Query hapus produk
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil dihapus!'); window.location.href = 'products.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus produk!'); window.location.href = 'products.php';</script>";
    }
    
    $stmt->close();
}

$conn->close();
?>
