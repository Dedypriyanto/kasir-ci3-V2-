<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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

        .content {
            margin-top: 60px;
            padding: 20px;
            width: calc(100% - 250px);
            margin-left: 250px;
            transition: 0.3s ease-in-out;
        }

        .sidebar.hidden {
            left: -250px;
        }
        .content.full {
            width: 100%;
            margin-left: 0;
        }

        /* Struk */
        .receipt {
            font-family: "Courier New", monospace;
            font-size: 12px;
            max-width: 250px;
            margin: auto;
            text-align: left;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        hr {
            border: none;
            border-top: 1px dashed black;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

        /* Cetak */
        @media print {
            .btn, .menu-toggle, .sidebar, .header { display: none; }
            .content { margin-left: 0; width: 100%; }
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
    <a href="<?php echo site_url('transaction'); ?>" class="active">💰 Transaksi</a>
    <a href="<?php echo site_url('report'); ?>">📋 Laporan</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="text-danger">🚪 Logout</a>
</div>

<!-- Konten Utama -->
<div class="content" id="content">

    <div class="receipt">
        <?php date_default_timezone_set('Asia/Jakarta'); ?> <!-- Set Zona Waktu Jakarta -->
        
        <div class="center bold">Yanto Shop</div>
        <div class="center">Jl. Contoh No. 123, Kota</div>
        <hr>
        <div><strong>Invoice:</strong> <?php echo htmlspecialchars($transaction->invoice_number); ?></div>
        <div><strong>Tanggal:</strong> <?php echo date("d/m/Y H:i"); ?> WIB</div>
        <div><strong>Kasir:</strong> <?php echo htmlspecialchars($transaction->cashier_name ?? ucfirst($this->session->userdata('username'))); ?></div>

        <div><strong>Nama:</strong> <?php echo htmlspecialchars($transaction->customer_name); ?></div>
        <div><strong>No Meja:</strong> <?php echo htmlspecialchars($transaction->table_number); ?></div>
        <hr>

        <?php foreach ($details as $detail): ?>
            <div><?php echo htmlspecialchars($detail->product_name); ?></div>
            <div>
                <?php echo intval($detail->quantity); ?> x Rp <?php echo number_format($detail->price, 0, ',', '.'); ?>
                <span class="right">Rp <?php echo number_format($detail->price * $detail->quantity, 0, ',', '.'); ?></span>
            </div>
            <div><small><strong>Catatan:</strong> <?php echo !empty($detail->note) ? htmlspecialchars($detail->note) : "-"; ?></small></div>
            <hr>
        <?php endforeach; ?>

        <div><strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars($transaction->payment_method ?? 'Tunai'); ?></div>
        <hr>
        <div class="bold right">Total: Rp <?php echo number_format($transaction->total_price, 0, ',', '.'); ?></div>
        <hr>
        <div class="center">Terima Kasih!</div>
        <hr>

        <button onclick="window.print()" class="btn btn-primary btn-sm">🖨 Cetak Struk</button>
        <a href="<?php echo site_url('transaction'); ?>" class="btn btn-secondary btn-sm">⬅ Kembali</a>
    </div>
</div>

<script>
    document.querySelector('.menu-toggle').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('hidden');
        document.querySelector('.content').classList.toggle('full');
    });

    // **Cetak Otomatis Saat Halaman Dibuka** (Opsional)
    window.onload = function () {
        setTimeout(() => {
            window.print();
        }, 500);
    };
</script>

</body>
</html>
