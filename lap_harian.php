<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "batik_store";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// EXPORT KE CSV
if (isset($_GET['download'])) {
    ob_clean();
    header("Content-Type: text/csv; charset=UTF-8");
    header("Content-Disposition: attachment; filename=laporan_harian.csv");

    $output = fopen("php://output", "w");
    // Tambahkan BOM UTF-8 agar file CSV bisa dibuka dengan benar di Excel
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, ["No", "Tanggal", "Produk", "Jumlah Terjual", "Pendapatan"], ";");

    $sql = "SELECT * FROM laporan_harian ORDER BY id DESC";
    $result = $conn->query($sql);
    $no = 1;

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $no,
            $row['tanggal'],
            $row['produk'],
            $row['jumlah_terjual'],
            $row['pendapatan']
        ], ";");
        $no++;
    }

    fclose($output);
    exit();
}

// EXPORT KE EXCEL
if (isset($_GET['download_excel'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Tanggal');
    $sheet->setCellValue('C1', 'Produk');
    $sheet->setCellValue('D1', 'Jumlah Terjual');
    $sheet->setCellValue('E1', 'Pendapatan');

    $sql = "SELECT * FROM laporan_harian ORDER BY id DESC";
    $result = $conn->query($sql);
    $no = 1;
    $rowNum = 2;

    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue("A$rowNum", $no);
        $sheet->setCellValue("B$rowNum", $row['tanggal']);
        $sheet->setCellValue("C$rowNum", $row['produk']);
        $sheet->setCellValue("D$rowNum", $row['jumlah_terjual']);
        $sheet->setCellValue("E$rowNum", $row['pendapatan']);
        $no++;
        $rowNum++;
    }

    // Set Auto Size untuk kolom
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = "laporan_harian.xlsx";

    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=$fileName");
    header("Cache-Control: max-age=0");

    $writer->save("php://output");
    exit();
}
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
              <a class="nav-link" href="accounts.php">
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
        <h2 class="mb-4 text-white">Laporan Harian Penjualan</h2>
        <a href="?download=true" class="btn btn-success mb-3">Download CSV</a>
        <a href="?download_excel=true" class="btn btn-primary mb-3">Download Excel</a>

        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Jumlah Terjual</th>
                        <th>Pendapatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM laporan_harian ORDER BY id DESC";
                    $result = $conn->query($sql);
                    $no = 1;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$no}</td>
                                <td>" . htmlspecialchars($row['tanggal']) . "</td>
                                <td>" . htmlspecialchars($row['produk']) . "</td>
                                <td>" . htmlspecialchars($row['jumlah_terjual']) . "</td>
                                <td>Rp " . htmlspecialchars($row['pendapatan']) . "</td>
                                <td>
                                    <a href='detail_lap.php?id=" . intval($row['id']) . "' class='btn btn-primary btn-sm'>Detail</a>
                                </td>
                            </tr>";
                            $no++;
                        }
                    }
                    ?>
                </tbody>
            </table>
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
    <script src="lap_harian.js"></script>
</body>
</html>
