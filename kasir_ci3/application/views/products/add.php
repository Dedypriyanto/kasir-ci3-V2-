<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Produk - Kasir C-Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }
        
        /* Header */
        .header {
            background: #343a40;
            color: white;
            padding: 10px 20px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
        }
        .menu-toggle {
            font-size: 24px;
            cursor: pointer;
            color: white;
            margin-right: 10px;
        }
        .header h5 {
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 20px;
            position: fixed;
            left: -250px;
            top: 50px;
            transition: left 0.3s ease-in-out;
            z-index: 999;
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
            flex-grow: 1;
            width: 100%;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Layar besar: Sidebar terbuka default */
        @media (min-width: 768px) {
            .sidebar {
                left: 0;
            }
            .content {
                margin-left: 250px;
            }
        }

        /* Mode Cetak */
        @media print {
            .btn, .menu-toggle, .header, .sidebar { display: none; }
            .content { margin-left: 0; margin-top: 0; }
        }
    </style>
  
</head>
<body>

    <!-- Header -->
    <div class="header">
        <span class="menu-toggle">☰</span>
        <h5 class="mb-0">Kasir C-Panel</h5>
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

    <!-- Konten Utama -->
    <div class="content">
        <div class="card shadow p-4 mt-3">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="category" class="form-select" required>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="price" id="price" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">✔ Simpan</button>
                <a href="<?php echo site_url('product'); ?>" class="btn btn-secondary">← Batal</a>
            </form>
        </div>
    </div>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        const menuToggle = document.querySelector('.menu-toggle');

        let isSidebarOpen = true; // Sidebar terbuka di layar besar

        menuToggle.addEventListener('click', function () {
            if (sidebar.style.left !== "0px") {
                sidebar.style.left = "0";
                content.style.marginLeft = "250px";
                isSidebarOpen = true;
            } else {
                if (window.innerWidth >= 768) {
                    sidebar.style.left = "-250px";
                    content.style.marginLeft = "0";
                    isSidebarOpen = false;
                }
            }
        });

        if (window.innerWidth >= 768) {
            sidebar.style.left = "0";
            content.style.marginLeft = "250px";
        }

        // Format angka saat user mengetik
        document.getElementById("price").addEventListener("input", function (e) {
            let value = e.target.value.replace(/\D/g, "");
            e.target.value = new Intl.NumberFormat("id-ID").format(value);
        });
    </script>

</body>
</html>
