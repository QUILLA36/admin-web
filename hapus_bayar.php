<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'batik_store';
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah ID ada dalam parameter URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM pembayaran WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Data berhasil dihapus'); window.location='pembayaran.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "ID tidak ditemukan.";
}

$conn->close();
?>
