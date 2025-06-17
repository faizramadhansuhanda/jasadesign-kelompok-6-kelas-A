<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";

 $tittle = "Tambah Karyawan - Narsis Room"; 
require_once "../template/header.php"; // Pastikan header.php memanggil $tittle jika didefinisikan
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$queryIdk = mysqli_query($koneksi, "SELECT max(idk) as maxidk FROM karyawan");
$data = mysqli_fetch_array($queryIdk);
$maxidk_from_db = $data["maxidk"] ?? null; // Menggunakan null coalescing untuk keamanan

$noUrut = 0; // Inisialisasi noUrut
if ($maxidk_from_db) {
    $noUrut = (int) substr($maxidk_from_db, 3, 3);
}
$noUrut++;

$generated_idk = "IDK".sprintf("%03s", $noUrut); // Ganti nama variabel agar tidak bingung

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tambah Karyawan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item "><a href="karyawan.php">Karyawan</a></li>
                <li class="breadcrumb-item active">Tambah Karyawan</li>
            </ol>
            <form action="proses-karyawan.php" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Karyawan</span>
                    <button type="submit" name="simpan" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    <button type="reset" name="reset" class="btn btn-danger float-end me-2"><i class="fa-solid fa-xmark"></i> Reset</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"> 
                            
                            <div class="mb-3 row">
                                <label for="idk" class="col-sm-2 col-form-label">IDK</label>
                                <div class="col-sm-10">
                                    <input type="text" name="idk" readonly class="form-control-plaintext border-bottom ps-2" id="idk" value="<?= $generated_idk ?>">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nama" required class="form-control border-0 border-bottom ps-2" id="nama" placeholder="Masukkan nama karyawan">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="posisi" class="col-sm-2 col-form-label">Posisi</label>
                                <div class="col-sm-10">
                                    <select name="posisi" id="posisi" class="form-select border-0 border-bottom" required>
                                        <option value="">-- Pilih Posisi --</option> 
                                        <option value="Designer">Designer</option>
                                        <option value="Copy Writer">Copy Writer</option>
                                        <option value="Editor">Editor</option>
                                        <option value="Photographer">Photographer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                   <textarea name="alamat" id="alamat" rows="3" placeholder="Alamat Karyawan" class="form-control" required></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4 text-center px-md-4 px-3">
                            <img src="../asset/image/icon.png" alt="Foto Karyawan" class="mb-3 img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                            <input type="file" name="image" class="form-control form-control-sm mb-2">
                            <small class="text-secondary d-block">Pilih foto: PNG, JPG, JPEG</small>
                            <small class="text-secondary d-block">Ukuran maks: 1 MB</small>
                            <small class="text-secondary d-block">Rasio 1:1 (disarankan)</small>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </main>

    <?php
    require_once "../template/footer.php";
    ?>
</div>