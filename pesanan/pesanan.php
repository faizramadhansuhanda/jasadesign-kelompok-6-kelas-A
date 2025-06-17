<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$tittle = "Tambah Pesanan - NARSIS ROOM";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tambah Pesanan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?= $main_url ?>index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= $main_url ?>pesanan/">Pesanan</a></li>
                <li class="breadcrumb-item active">Tambah Pesanan</li>
            </ol>

            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-plus"></i> Tambah Pesanan Baru</span>
                </div>
                <div class="card-body">
                    <form action="proses-tambah-pesanan.php" method="post">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_invoice" class="form-label">ID Invoice</label>
                                    <input type="text" class="form-control" id="id_invoice" value="Auto-generated" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_pesanan" class="form-label">Tanggal Pesanan</label>
                                    <input type="date" class="form-control" id="tanggal_pesanan" name="tanggal_pesanan" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_client" class="form-label">Client</label>
                                    <select name="id_client" id="id_client" class="form-select" required>
                                        <option value="" selected disabled>Pilih Client</option>
                                        <?php

                                        $queryClient = mysqli_query($koneksi, "SELECT idc, nama FROM client ORDER BY nama");
                                        while ($client = mysqli_fetch_array($queryClient)) {

                                            echo '<option value="' . $client['idc'] . '">' . $client['nama'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_karyawan" class="form-label">Karyawan</label>
                                    <select name="id_karyawan" id="id_karyawan" class="form-select" required>
                                        <option value="" selected disabled>Pilih Karyawan</option>
                                        <?php

                                        $queryKaryawan = mysqli_query($koneksi, "SELECT idk, nama FROM karyawan ORDER BY nama");
                                        while ($karyawan = mysqli_fetch_array($queryKaryawan)) {

                                            echo '<option value="' . $karyawan['idk'] . '">' . $karyawan['nama'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5>Rincian Layanan</h5>
                        <div id="rincian_layanan_wrapper">
                            <div class="row mb-3 layanan-item">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Layanan</label>
                                    <select name="id_layanan[]" class="form-select layanan-select" required>
                                        <option value="" selected disabled>Pilih Layanan</option>
                                        <?php
                                        // Query untuk mengambil semua layanan yang tersedia
                                        $queryLayanan = mysqli_query($koneksi, "SELECT id_layanan, nama_layanan, harga FROM layanan ORDER BY nama_layanan");
                                        while ($layanan = mysqli_fetch_assoc($queryLayanan)) {
                                            // PERBAIKAN: 
                                            // 1. Menggunakan id_layanan sebagai 'value'
                                            // 2. Menambahkan atribut 'data-harga' untuk JavaScript
                                            echo '<option value="' . htmlspecialchars($layanan['id_layanan']) . '" data-harga="' . htmlspecialchars($layanan['harga']) . '">' . htmlspecialchars($layanan['nama_layanan']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Harga</label>
                                    <input type="number" name="harga[]" class="form-control harga-layanan" readonly>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah[]" class="form-control jumlah-layanan" value="1" min="1" required>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Subtotal</label>
                                    <input type="number" name="subtotal[]" class="form-control subtotal-layanan" readonly>
                                </div>
                                <div class="col-md-1 mb-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm hapus-layanan-btn">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="tambah_layanan_btn" class="btn btn-outline-primary mb-4"><i class="fa-solid fa-plus"></i> Tambah Layanan</button>

                        <hr>

                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <div class="mb-3 row">
                                    <label for="total_harga" class="col-sm-4 col-form-label fs-5">Total Harga</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-lg" id="total_harga" name="total_akhir" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" name="simpan" class="btn btn-primary me-2">Selesai</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php require_once "../template/footer.php"; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('rincian_layanan_wrapper');

        function calculateRow(row) {
            const harga = parseFloat(row.querySelector('.harga-layanan').value) || 0;
            const jumlah = parseInt(row.querySelector('.jumlah-layanan').value) || 0;
            const subtotal = harga * jumlah;
            row.querySelector('.subtotal-layanan').value = subtotal;
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal-layanan').forEach(function(subtotalField) {
                total += parseFloat(subtotalField.value) || 0;
            });
            document.getElementById('total_harga').value = formatRupiah(total.toString());
        }

        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        wrapper.addEventListener('change', function(e) {
            if (e.target.classList.contains('layanan-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const harga = selectedOption.getAttribute('data-harga');
                const row = e.target.closest('.layanan-item');
                row.querySelector('.harga-layanan').value = harga;
                calculateRow(row);
            }
        });

        wrapper.addEventListener('input', function(e) {
            if (e.target.classList.contains('jumlah-layanan')) {
                const row = e.target.closest('.layanan-item');
                calculateRow(row);
            }
        });

        wrapper.addEventListener('click', function(e) {
            if (e.target.classList.contains('hapus-layanan-btn')) {
                if (document.querySelectorAll('.layanan-item').length > 1) {
                    e.target.closest('.layanan-item').remove();
                    calculateTotal();
                } else {
                    alert('Minimal harus ada satu layanan.');
                }
            }
        });

        document.getElementById('tambah_layanan_btn').addEventListener('click', function() {
            const firstItem = wrapper.querySelector('.layanan-item');
            const newItem = firstItem.cloneNode(true);
            newItem.querySelector('select').selectedIndex = 0;
            newItem.querySelectorAll('input').forEach(input => input.value = '');
            newItem.querySelector('.jumlah-layanan').value = '1';
            wrapper.appendChild(newItem);
        });
    });
</script>