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

$id = $_GET['id'] ?? '';
if (!$id) {
    die("ID tidak ditemukan!");
}

// Hapus data
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: accounts.php");
    exit();
} else {
    echo "Gagal menghapus pengguna!";
}

$conn->close();
?>
