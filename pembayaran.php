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

// Ambil data pembayaran dari database
$query = "SELECT * FROM pembayaran";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <h2>Data Pembayaran</h2>
    <table id="tabel_pembayaran" class="display">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Item Price</th>
                <th>Status Transaksi</th>
                <th>Hapus</th>
            </tr>
        </thead>
        <tbody>
    <?php 
    $data_pembayaran = [];
    while ($data = $result->fetch_assoc()) {
        $data_pembayaran[] = $data;
    }
    $data_pembayaran = array_reverse($data_pembayaran); // Balik urutan data

    $no = 1; // Mulai nomor dari 1
    foreach ($data_pembayaran as $data): 
    ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $data['nama']; ?></td>
            <td><?= number_format($data['item_price'], 0, ',', '.'); ?></td>
            <td><?= $data['status']; ?></td>
            <td><button onclick="hapusData(<?= $data['id']; ?>)">Hapus</button></td>
        </tr>
    <?php endforeach; ?>
</tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#tabel_pembayaran').DataTable({
                "language": {
                    "lengthMenu": "Menampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });

        function hapusData(id) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                window.location.href = 'hapus_bayar.php?id=' + id;
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
