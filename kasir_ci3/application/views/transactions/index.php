<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Transaksi - Kasir C-Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    <!-- Main Content -->
    <div class="content" id="content">
    <h2 class="text-primary fw-bold">💰 Tambah Transaksi</h2>

        <div class="card p-3 shadow-sm">
            <form method="post" action="<?php echo site_url('transaction/add'); ?>">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label"><b>Nama Pelanggan:</b></label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Masukkan Nama" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><b>Nomor Meja:</b></label>
                        <input type="number" name="table_number" class="form-control" placeholder="Masukkan Nomor" required>
                    </div>
                </div>

                <label class="form-label mt-3"><b>Pilih Menu:</b></label>
                <div id="product-list">
                    <div class="product-entry d-flex gap-2 align-items-center mb-2">
                        <select name="product_id[]" class="form-select product_id" required>
                            <option value="">Pilih Produk</option>
                            <optgroup label="Makanan">
                                <?php foreach ($products as $product) : if ($product->category == 'Makanan') : ?>
                                    <option value="<?php echo $product->id; ?>" data-price="<?php echo $product->price; ?>">
                                        <?php echo $product->name . " - Rp " . number_format($product->price, 0, ',', '.'); ?>
                                    </option>
                                <?php endif; endforeach; ?>
                            </optgroup>
                            <optgroup label="Minuman">
                                <?php foreach ($products as $product) : if ($product->category == 'Minuman') : ?>
                                    <option value="<?php echo $product->id; ?>" data-price="<?php echo $product->price; ?>">
                                        <?php echo $product->name . " - Rp " . number_format($product->price, 0, ',', '.'); ?>
                                    </option>
                                <?php endif; endforeach; ?>
                            </optgroup>
                        </select>
                        <input type="number" name="quantity[]" class="form-control quantity" min="1" required>
                        <input type="text" name="note[]" class="form-control" placeholder="Catatan (Opsional)">
                        <button type="button" class="btn btn-danger btn-sm remove-product">Hapus</button>
                    </div>
                </div>
                <button type="button" id="add-product" class="btn btn-primary btn-sm">+ Tambah Produk</button>
                <h5 class="mt-3" id="total">Total Harga: Rp 0</h5>
                <button type="submit" class="btn btn-success mt-2">✔ Simpan Transaksi</button>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('hidden');
            document.querySelector('.content').classList.toggle('full');
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("add-product").addEventListener("click", function () {
                let productList = document.getElementById("product-list");
                let newEntry = document.createElement("div");
                newEntry.classList.add("product-entry", "d-flex", "gap-2", "align-items-center", "mb-2");

                newEntry.innerHTML = document.querySelector(".product-entry").innerHTML;
                productList.appendChild(newEntry);
            });

            document.getElementById("product-list").addEventListener("click", function (event) {
                if (event.target.classList.contains("remove-product")) {
                    event.target.parentElement.remove();
                }
            });

            document.getElementById("product-list").addEventListener("input", function () {
                updateTotal();
            });

            function updateTotal() {
                let total = 0;
                document.querySelectorAll(".product-entry").forEach(entry => {
                    let productSelect = entry.querySelector(".product_id");
                    let quantityInput = entry.querySelector(".quantity");

                    let price = productSelect.options[productSelect.selectedIndex]?.getAttribute("data-price") || 0;
                    let quantity = quantityInput.value ? parseInt(quantityInput.value) : 0;

                    total += price * quantity;
                });

                document.getElementById("total").innerText = "Total Harga: Rp " + total.toLocaleString("id-ID");
            }
        });
    </script>

</body>
</html>
