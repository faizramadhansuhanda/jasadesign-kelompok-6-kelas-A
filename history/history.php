<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$tittle = "History Pesanan - NARSIS ROOM";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}
$alert = '';
if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Data pesanan baru berhasil ditambahkan!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Data pesanan berhasil dihapus!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Data pesanan berhasil diperbarui!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">History Pesanan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">History Pesanan</li>
            </ol>
            <?php if ($msg != "") {
                echo $alert;
            } ?>
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pesanan</span>
                    <a href="<?= $main_url ?>pesanan/pesanan.php" class="btn btn-sm btn-primary float-end"><i class="fa-solid fa-plus"></i> Tambah Pesanan </a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">ID Invoice</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Client</th>
                                <th class="text-center">Karyawan</th>
                                <th class="text-center">Total Harga</th>
                                <th class="text-center">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $queryPesanan = mysqli_query($koneksi, "
                                SELECT 
                                    p.id_pesanan,
                                    p.kode_invoice,
                                    p.tanggal_pesanan,
                                    c.nama AS nama_client,
                                    k.nama AS nama_karyawan,
                                    IFNULL(r_totals.total, 0) AS total_harga
                                FROM 
                                    pesanan p
                                LEFT JOIN 
                                    client c ON p.id_client = c.idc
                                LEFT JOIN 
                                    karyawan k ON p.id_karyawan = k.idk
                                LEFT JOIN 
                                    (SELECT id_pesanan, SUM(subtotal) AS total FROM rincian GROUP BY id_pesanan) AS r_totals 
                                    ON p.id_pesanan = r_totals.id_pesanan
                                ORDER BY 
                                    p.tanggal_pesanan DESC, p.id_pesanan DESC
                            ");

                            while ($data = mysqli_fetch_assoc($queryPesanan)) { ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($data['kode_invoice']) ?></td>
                                    <td><?= date('d M Y', strtotime($data['tanggal_pesanan'])) ?></td>
                                    <td><?= htmlspecialchars($data['nama_client']) ?></td>
                                    <td><?= htmlspecialchars($data['nama_karyawan']) ?></td>
                                    <td class="text-end"><?= 'Rp ' . number_format($data['total_harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="../pesanan/edit-pesanan.php?id=<?= $data['id_pesanan'] ?>" class="btn btn-sm btn-info" title="Lihat/Edit Pesanan"><i class="fa-solid fa-eye"></i></a>
                                        <a href="cetak-invoice.php?invoice=<?= $data['kode_invoice'] ?>" class="btn btn-sm btn-secondary" title="Cetak Invoice" target="_blank"><i class="fa-solid fa-print"></i></a>
                                        
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalHapus"
                                                data-id="<?= $data['id_pesanan'] ?>"
                                                data-invoice="<?= htmlspecialchars($data['kode_invoice']) ?>"
                                                title="Hapus Pesanan">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalHapus" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin akan menghapus data pesanan dengan ID Invoice: <br> <strong id="invoiceDihapus"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="btnKonfirmasiHapus" class="btn btn-danger">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "../template/footer.php"; ?>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', ".btn-hapus", function() {
        const idPesanan = $(this).data('id');
        const kodeInvoice = $(this).data('invoice');
        
        $('#invoiceDihapus').text(kodeInvoice);
        
        const urlHapus = "hapus-pesanan.php?id=" + idPesanan;
        
        $('#btnKonfirmasiHapus').attr("href", urlHapus);
        
        $('#mdlHapus').modal('show');
    });
});
</script>