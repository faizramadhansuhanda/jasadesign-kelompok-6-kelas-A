<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$tittle = "Update Client - NARSIS ROOM";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$idc = $_GET['idc'] ?? null;
if (!$idc) {
    echo "<script>alert('ID Client tidak valid di URL!'); window.location='client.php';</script>";
    exit;
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM client WHERE idc = ?");
mysqli_stmt_bind_param($stmt, "s", $idc);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    echo "<script>alert('Data client tidak ditemukan!'); window.location='client.php';</script>";
    exit;
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Update Client</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item "><a href="client.php">Client</a></li>
                <li class="breadcrumb-item active">Update Client</li>
            </ol>
            <form action="proses-client.php" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Update Client</span>
                        <button type="submit" name="update" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <input type="hidden" name="idc" value="<?= htmlspecialchars($data['idc']) ?>">
                                
                                <div class="mb-3 row">
                                    <label for="idc" class="col-sm-2 col-form-label">ID Client</label>
                                    <label for="idc" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <input type="text" name="idc_display" tittle="ID Client" class="form-control-plaintext border-bottom ps-2" readonly id="idc" value="<?= htmlspecialchars($data['idc']) ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                    <label for="nis" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <input type="text" name="nama" required class="form-control border-0 border-bottom ps-2" value="<?= htmlspecialchars($data['nama']) ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="telpon" class="col-sm-2 col-form-label">No HP</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <input type="tel" name="telpon" pattern="[0-9]{5,}" title="12 angka" required class="form-control border-0 border-bottom ps-2" value="<?= htmlspecialchars($data['telpon']) ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <input type="email" name="email" required class="form-control border-0 border-bottom ps-2" id="email" value="<?= htmlspecialchars($data['email']) ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <textarea name="alamat" id="alamat" cols="30" rows="3" placeholder="Alamat Client" class="form-control" required><?= htmlspecialchars($data['alamat']) ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php require_once "../template/footer.php"; ?>
</div>