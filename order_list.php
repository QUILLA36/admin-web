<?php
$servername = "localhost";
$username = "root"; // Ganti sesuai dengan user MySQL
$password = ""; // Ganti jika ada password
$dbname = "batik_store";

// Buat koneksi ke MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data pesanan
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Order</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Daftar Pesanan</h2>
    <table>
        <tr>
            <th>NO ORDER</th>
            <th>STATUS</th>
            <th>OPERATOR</th>
            <th>LOKASI</th>
            <th>JARAK</th>
            <th>TANGGAL PESANAN</th>
            <th>JATUH TEMPO PENGIRIMAN</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['order_number']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['operator_name']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['distance_km']} km</td>
                        <td>" . date('H:i, d M Y', strtotime($row['order_time'])) . "</td>
                        <td>" . date('H:i, d M Y', strtotime($row['due_time'])) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada pesanan</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
