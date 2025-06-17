<?php


session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$tittle = "Edit Pesanan - NARSIS ROOM";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";


$id_pesanan = $_GET['id'] ?? null;
if (!$id_pesanan) {
    echo "<script>alert('ID Pesanan tidak valid di URL!'); window.location='../history/history.php';</script>";
    exit;
}


$stmt_pesanan = mysqli_prepare($koneksi, "SELECT * FROM pesanan WHERE id_pesanan = ?");
mysqli_stmt_bind_param($stmt_pesanan, "i", $id_pesanan);
mysqli_stmt_execute($stmt_pesanan);
$result_pesanan = mysqli_stmt_get_result($stmt_pesanan);
$data_pesanan = mysqli_fetch_assoc($result_pesanan);
mysqli_stmt_close($stmt_pesanan);

if (!$data_pesanan) {
    echo "<script>alert('Data pesanan tidak ditemukan!'); window.location='../history/history.php';</script>";
    exit;
}


$stmt_detail = mysqli_prepare($koneksi, "SELECT * FROM rincian WHERE id_pesanan = ?");
mysqli_stmt_bind_param($stmt_detail, "i", $id_pesanan); // "i" untuk integer
mysqli_stmt_execute($stmt_detail);
$result_detail = mysqli_stmt_get_result($stmt_detail);
$detail_pesanan_items = [];
while ($row = mysqli_fetch_assoc($result_detail)) {
    $detail_pesanan_items[] = $row;
}
mysqli_stmt_close($stmt_detail);

$queryLayanan = mysqli_query($koneksi, "SELECT id_layanan, nama_layanan, harga FROM layanan ORDER BY nama_layanan");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit Pesanan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?= $main_url ?>index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="../history/history.php">History Pesanan</a></li>
                <li class="breadcrumb-item active">Edit Pesanan</li>
            </ol>

            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Edit Pesanan</span>
                </div>
                <div class="card-body">
                    <form action="proses-tambah-pesanan.php" method="post">
                        <input type="hidden" name="action_update" value="1">
                        <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($data_pesanan['id_pesanan']) ?>">
                        <input type="hidden" name="kode_invoice" value="<?= htmlspecialchars($data_pesanan['kode_invoice']) ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_invoice_display" class="form-label">ID Invoice</label>
                                    <input type="text" class="form-control" id="kode_invoice_display" value="<?= htmlspecialchars($data_pesanan['kode_invoice']) ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_pesanan" class="form-label">Tanggal Pesanan</label>
                                    <input type="date" class="form-control" id="tanggal_pesanan" name="tanggal_pesanan" value="<?= htmlspecialchars($data_pesanan['tanggal_pesanan']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_client" class="form-label">Client</label>
                                    <select name="id_client" id="id_client" class="form-select" required>
                                        <option value="">Pilih Client</option>
                                        <?php
                                        $queryClient = mysqli_query($koneksi, "SELECT idc, nama FROM client ORDER BY nama");
                                        while ($client = mysqli_fetch_array($queryClient)) {
                                            $selected = ($client['idc'] == $data_pesanan['id_client']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($client['idc']) . '" ' . $selected . '>' . htmlspecialchars($client['nama']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_karyawan" class="form-label">Karyawan</label>
                                    <select name="id_karyawan" id="id_karyawan" class="form-select" required>
                                        <option value="">Pilih Karyawan</option>
                                        <?php
                                        $queryKaryawan = mysqli_query($koneksi, "SELECT idk, nama FROM karyawan ORDER BY nama");
                                        while ($karyawan = mysqli_fetch_array($queryKaryawan)) {
                                            $selected = ($karyawan['idk'] == $data_pesanan['id_karyawan']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($karyawan['idk']) . '" ' . $selected . '>' . htmlspecialchars($karyawan['nama']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5>Rincian Layanan</h5>
                        <div id="rincian_layanan_wrapper">
                            <?php
                            if (empty($detail_pesanan_items)) {
                                $detail_pesanan_items[] = ['id_layanan' => '', 'harga' => '', 'jumlah' => 1, 'subtotal' => ''];
                            }

                            foreach ($detail_pesanan_items as $item) : ?>
                                <div class="row mb-3 layanan-item">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label">Layanan</label>
                                        <select name="id_layanan[]" class="form-select layanan-select" required>
                                            <option value="">Pilih Layanan</option>
                                            <?php
                                            mysqli_data_seek($queryLayanan, 0);
                                            while ($layanan = mysqli_fetch_array($queryLayanan)) {
                                                $selected = ($layanan['id_layanan'] == $item['id_layanan']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($layanan['id_layanan']) . '" data-harga="' . htmlspecialchars($layanan['harga']) . '" ' . $selected . '>' . htmlspecialchars($layanan['nama_layanan']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">Harga</label>
                                        <input type="number" name="harga[]" class="form-control harga-layanan" value="<?= htmlspecialchars($item['harga']) ?>" readonly>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="jumlah[]" class="form-control jumlah-layanan" value="<?= htmlspecialchars($item['jumlah']) ?>" min="1" required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Subtotal</label>
                                        <input type="number" name="subtotal[]" class="form-control subtotal-layanan" value="<?= htmlspecialchars($item['subtotal']) ?>" readonly>
                                    </div>
                                    <div class="col-md-1 mb-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm hapus-layanan-btn">Hapus</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" id="tambah_layanan_btn" class="btn btn-outline-primary mb-4"><i class="fa-solid fa-plus"></i> Tambah Layanan</button>

                        <hr>

                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <div class="mb-3 row">
                                    <label for="total_harga" class="col-sm-4 col-form-label fs-5">Total Harga</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-lg" id="total_harga" name="total_akhir" value="<?= 'Rp ' . number_format($data_pesanan['total_harga'] ?? 0, 0, ',', '.') ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" name="action_update" class="btn btn-primary me-2">Update Pesanan</button>
                            <a href="../history/history.php" class="btn btn-secondary">Batal</a>
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
            if (!row) return;
            const hargaInput = row.querySelector('.harga-layanan');
            const jumlahInput = row.querySelector('.jumlah-layanan');
            const subtotalInput = row.querySelector('.subtotal-layanan');

            const harga = parseFloat(hargaInput.value) || 0;
            const jumlah = parseInt(jumlahInput.value) || 0;
            const subtotal = harga * jumlah;

            subtotalInput.value = subtotal;
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
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        wrapper.addEventListener('input', function(e) {
            if (e.target.classList.contains('jumlah-layanan')) {
                calculateRow(e.target.closest('.layanan-item'));
            }
        });

        wrapper.addEventListener('change', function(e) {
            if (e.target.classList.contains('layanan-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const harga = selectedOption.getAttribute('data-harga') || 0;
                const row = e.target.closest('.layanan-item');

                row.querySelector('.harga-layanan').value = harga;
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
            if (!firstItem) return;
            const newItem = firstItem.cloneNode(true);

            newItem.querySelector('.layanan-select').value = "";
            newItem.querySelector('.harga-layanan').value = "";
            newItem.querySelector('.jumlah-layanan').value = "1";
            newItem.querySelector('.subtotal-layanan').value = "";

            wrapper.appendChild(newItem);
        });

        calculateTotal();
    });
</script>