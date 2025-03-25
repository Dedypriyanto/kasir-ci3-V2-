<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Produk - Kasir C-Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
                window.location.href = "<?php echo site_url('product/delete/'); ?>" + id;
            }
        }
    </script>

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
        }

        /* Header */
        .header {
            width: 100%;
            height: 50px;
            background: #343a40;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
        }
        .menu-toggle {
            font-size: 24px;
            cursor: pointer;
            color: white;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            margin-left: 10px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 20px;
            position: fixed;
            top: 50px;
            left: 0;
            transition: 0.3s ease-in-out;
            z-index: 1000;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #495057;
        }

        /* Kasir C-Panel hanya di tampilan mobile */
        .sidebar .mobile-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar .mobile-title {
                display: block;
            }
        }

        /* Konten */
        .content {
            margin-top: 60px;
            padding: 20px;
            width: calc(100% - 250px);
            margin-left: 250px;
            transition: 0.3s ease-in-out;
        }

        /* Sidebar Tersembunyi */
        .sidebar.hidden {
            left: -250px;
        }
        .content.full {
            width: 100%;
            margin-left: 0;
        }
        
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <span class="menu-toggle">☰</span>
        <h5 class="header-title">Kasir C-Panel</h5>
    </div>

  <!-- Sidebar -->
<div class="sidebar">
    <h5 class="mobile-title text-light d-md-none">Kasir C-Panel</h5>
    <a href="<?php echo site_url('home'); ?>">🏠 Dashboard</a>
    <a href="<?php echo site_url('product'); ?>">📦 Kelola Produk</a>
    <a href="<?php echo site_url('transaction'); ?>" >💰 Transaksi</a>
    <a href="<?php echo site_url('report'); ?>"class="active">📋 Laporan</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="text-danger">🚪 Logout</a>
</div>

    <!-- Main Content -->
<!-- Main Content -->
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-primary fw-bold">📋 Laporan Transaksi</h2>
    <button id="printButton" class="btn btn-secondary" onclick="printReport()">🖨 Cetak Laporan</button>
    </div>

    <!-- Filter Bulan, Tahun & Pencarian -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="monthFilter">📅 Filter Bulan:</label>
            <select id="monthFilter" class="form-select" onchange="filterTable()">
                <option value="">Semua Bulan</option>
                <?php for ($m = 1; $m <= 12; $m++) : ?>
                    <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>">
                        <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="yearFilter">📆 Filter Tahun:</label>
            <select id="yearFilter" class="form-select" onchange="filterTable()">
                <option value="">Semua Tahun</option>
                <?php for ($y = date('Y'); $y >= 2000; $y--) : ?>
                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="searchInput">🔍 Cari Transaksi:</label>
            <input type="text" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari berdasarkan Invoice atau Produk...">
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card shadow-sm p-3">
        <table id="transactionTable" class="table table-hover table-bordered text-center">
            <thead class="table-dark text-light">
                <tr>
                    <th>Nomor Invoice</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)) : ?>
                    <?php foreach ($transactions as $transaction) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction->invoice_number); ?></td>
                        <td><?php echo isset($transaction->product_names) ? htmlspecialchars($transaction->product_names) : "Produk tidak tersedia"; ?></td>
                        <td><?php echo isset($transaction->quantity) ? intval($transaction->quantity) : 0; ?></td>
                        <td>Rp <?php echo number_format($transaction->total_price, 0, ',', '.'); ?></td>
                        <td><?php echo date("d-m-Y H:i", strtotime($transaction->created_at)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-muted">❌ Tidak ada transaksi ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('hidden');
            document.querySelector('.content').classList.toggle('full');
        });
    function printReport() {
        var printContents = document.getElementById("transactionTable").outerHTML;
        var newWindow = window.open('', '_blank');

        newWindow.document.write(`
            <html>
            <head>
                <title>Cetak Laporan</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; margin: 0; padding: 0; }
                    h2 { display: block; width: 100%; margin: 20px 0 10px 0; font-size: 22px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { padding: 8px; border: 1px solid black; text-align: left; }
                    thead { background-color:rgb(251, 253, 255); color: black; }
                </style>
            </head>
            <body>
                <h2>📋 Laporan Transaksi</h2>
                ${printContents}
            </body>
            </html>
        `);
        
        newWindow.document.close();
        newWindow.print();
    }

    function filterTable() {
        var month = document.getElementById("monthFilter").value;
        var year = document.getElementById("yearFilter").value;
        var search = document.getElementById("searchInput").value.toLowerCase();
        var table = document.getElementById("transactionTable");
        var rows = table.getElementsByTagName("tr");

        for (var i = 1; i < rows.length; i++) { // Mulai dari 1 karena 0 adalah header
            var invoice = rows[i].getElementsByTagName("td")[0].innerText.toLowerCase();
            var product = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase();
            var date = rows[i].getElementsByTagName("td")[4].innerText;

            // Ambil bulan dan tahun dari tanggal transaksi
            var transactionMonth = date.split("-")[1]; // Format tanggal: dd-mm-yyyy HH:MM
            var transactionYear = date.split("-")[2].split(" ")[0];

            // Filter pencarian invoice atau produk
            var searchMatch = invoice.includes(search) || product.includes(search);

            // Filter bulan & tahun
            var monthMatch = month === "" || transactionMonth === month;
            var yearMatch = year === "" || transactionYear === year;

            // Tampilkan baris jika semua kondisi cocok
            if (searchMatch && monthMatch && yearMatch) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    // Pastikan fungsi dipanggil saat input berubah
    document.getElementById("monthFilter").addEventListener("change", filterTable);
    document.getElementById("yearFilter").addEventListener("change", filterTable);
    document.getElementById("searchInput").addEventListener("keyup", filterTable);

    </script>

</body>
</html>
