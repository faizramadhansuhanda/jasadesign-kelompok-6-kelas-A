<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$tittle = "Client - NARSIS ROOM";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}
$alert = '';
if ($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Data client berhasil dihapus!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Data client berhasil diperbarui!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'cancel' || $msg == 'idc_exists') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-xmark"></i> Data client gagal diperbarui! ID Client sudah terdaftar.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'notfound') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation"></i> Data client tidak ditemukan atau sudah dihapus sebelumnya.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if ($msg == 'error') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-xmark"></i> Terjadi kesalahan saat menghapus data.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Data Client</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Client</li>
            </ol>
            
            <?php if ($msg != "") {
                echo $alert;
            } ?>

            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-list"></i> Data Client</span>
                    <a href="<?= $main_url ?>client/add-client.php" class="btn btn-sm btn-primary float-end"><i class="fa-solid fa-plus"></i> Tambah Client</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col">ID Client</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Telepon</th>
                                <th scope="col">Email</th>
                                <th scope="col">Alamat</th>
                                <th scope="col" class="text-center">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $queryClient = mysqli_query($koneksi, "SELECT * FROM client");
                            while ($data = mysqli_fetch_assoc($queryClient)) { ?>
                                <tr>
                                    <th scope="row" class="text-center"><?= $no++ ?></th>
                                
                                    <td><?= htmlspecialchars($data['idc']) ?></td>
                                    <td><?= htmlspecialchars($data['nama']) ?></td>
                                    <td><?= htmlspecialchars($data['telpon']) ?></td>
                                    <td><?= htmlspecialchars($data['email']) ?></td>
                                    <td><?= htmlspecialchars($data['alamat']) ?></td>
                                    <td class="text-center">
                                       
                                        <a href="edit-client.php?idc=<?= $data['idc'] ?>" class="btn btn-sm btn-warning" title="Update Client"><i class="fa-solid fa-pen"></i></a>
                                        
                                      
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalHapus"
                                                data-idc="<?= $data['idc'] ?>"
                                                data-nama="<?= htmlspecialchars($data['nama']) ?>"
                                                title="Hapus Client">
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
                    <p>Anda yakin akan menghapus data client: <strong id="namaClientDihapus"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="hapus-client.php" id="btnKonfirmasiHapus" class="btn btn-danger">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "../template/footer.php"; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalHapus = document.getElementById('modalHapus');
    

    if(modalHapus) {
        modalHapus.addEventListener('show.bs.modal', function (event) {
       
            const button = event.relatedTarget;
            
         
            const clientIdc = button.getAttribute('data-idc');
            const clientNama = button.getAttribute('data-nama');
            
        
            const urlHapus = `hapus-client.php?idc=${clientIdc}`; 

         
            const namaClientElement = modalHapus.querySelector('#namaClientDihapus');
            namaClientElement.textContent = clientNama;
            
       
            const btnKonfirmasiHapus = modalHapus.querySelector('#btnKonfirmasiHapus');
            btnKonfirmasiHapus.setAttribute('href', urlHapus);
        });
    }
});
</script>

    <?php

    require_once "../template/footer.php";
    ?>