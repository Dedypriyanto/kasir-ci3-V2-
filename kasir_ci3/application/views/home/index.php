<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard C-Panel - Kasir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-key.js" crossorigin="anonymous"></script> 
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
        .header h5 {
            margin: 0;
            font-size: 18px;
        }
        .menu-toggle {
            font-size: 24px;
            cursor: pointer;
            color: white;
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
        .sidebar h2 {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #495057;
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
    <a href="<?php echo site_url('home'); ?>"class="active">🏠 Dashboard</a>
    <a href="<?php echo site_url('product'); ?>">📦 Kelola Produk</a>
    <a href="<?php echo site_url('transaction'); ?>">💰 Transaksi</a>
    <a href="<?php echo site_url('report'); ?>">📋 Laporan</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="text-danger">🚪 Logout</a>
</div>

    <!-- Main Content -->
    <div class="content">
        <h2 class="text-primary fw-bold">Selamat Datang, <strong><?php echo $this->session->userdata('username'); ?></strong>!</h2>
        <p>Anda login sebagai: <strong><?php echo ucfirst($this->session->userdata('username')); ?></strong></p>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <a href="<?php echo site_url('product'); ?>" class="text-decoration-none">
                    <div class="card p-4 text-center dashboard-card shadow-sm bg-white">
                        <i class="fas fa-box text-primary"></i>
                        <h5 class="fw-bold text-dark">Kelola Produk</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?php echo site_url('transaction'); ?>" class="text-decoration-none">
                    <div class="card p-4 text-center dashboard-card shadow-sm bg-white">
                        <i class="fas fa-shopping-cart text-success"></i>
                        <h5 class="fw-bold text-dark">Kelola Transaksi</h5>
                    </div>
                </a>
            </div>

            <?php if ($this->session->userdata('role') == 'operator'): ?>
                <div class="col-md-4">
                    <a href="<?php echo site_url('report'); ?>" class="text-decoration-none">
                        <div class="card p-4 text-center dashboard-card shadow-sm bg-white">
                            <i class="fas fa-chart-line text-warning"></i>
                            <h5 class="fw-bold text-dark">Laporan</h5>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
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
