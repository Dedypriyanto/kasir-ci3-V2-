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
    <a href="<?php echo site_url('product'); ?>" class="active">📦 Kelola Produk</a>
    <a href="<?php echo site_url('transaction'); ?>">💰 Transaksi</a>
    <a href="<?php echo site_url('report'); ?>">📋 Laporan</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="text-danger">🚪 Logout</a>
</div>

    <!-- Main Content -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary fw-bold">📦Daftar Produk</h2>
            <a href="<?php echo site_url('product/add'); ?>" class="btn btn-success">+ Tambah Produk</a>
        </div>

        <!-- Produk Makanan -->
        <div class="card shadow p-3 mb-4">
            <h4 class="text-primary">Makanan</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($makanan)) : ?>
                            <?php foreach ($makanan as $item) : ?>
                                <tr>
                                    <td><?php echo $item->id; ?></td>
                                    <td><?php echo htmlspecialchars($item->name); ?></td>
                                    <td>Rp <?php echo number_format($item->price, 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="<?php echo site_url('product/edit/'.$item->id); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <button onclick="confirmDelete(<?php echo $item->id; ?>)" class="btn btn-danger btn-sm">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-muted">❌ Tidak ada makanan tersedia</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Produk Minuman -->
        <div class="card shadow p-3">
            <h4 class="text-primary">Minuman</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($minuman)) : ?>
                            <?php foreach ($minuman as $item) : ?>
                                <tr>
                                    <td><?php echo $item->id; ?></td>
                                    <td><?php echo htmlspecialchars($item->name); ?></td>
                                    <td>Rp <?php echo number_format($item->price, 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="<?php echo site_url('product/edit/'.$item->id); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <button onclick="confirmDelete(<?php echo $item->id; ?>)" class="btn btn-danger btn-sm">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-muted">❌ Tidak ada minuman tersedia</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('hidden');
            document.querySelector('.content').classList.toggle('full');
        });
    </script>

</body>
</html>
