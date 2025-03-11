document.getElementById("downloadBtn").addEventListener("click", function () {
    let csvContent = "No;Tanggal;Produk;Jumlah Terjual;Pendapatan\n";
    csvContent += "--------------------------------------------\n"; // Tambah garis pemisah

    salesData.forEach((row, index) => {
        csvContent += `${index + 1};${row.date};${row.product};${row.sold};${row.revenue}\n`;
    });

    csvContent += "--------------------------------------------\n"; // Tambah garis pemisah di akhir

    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "laporan_penjualan.csv";
    link.click();
});
