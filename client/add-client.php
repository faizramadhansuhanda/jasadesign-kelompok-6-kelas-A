<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$tittle = "Tambah Client - NARSIS ROOM";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";


if (isset($_GET['msg'])) {
    $msg = $_GET ['msg'];
} else {
    $msg = '';
}
$alert = '';
if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-xmark"></i> Tambah Client gagal, IDC sudah ada.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'notimage') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-xmark"></i> Tambah guru gagal, file yang anda upload bukan gambar.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Tambah Client berhasil !
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tambah Client</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item "><a href="guru.php">Client</a></li>
                <li class="breadcrumb-item active">Tambah Client</li>
            </ol>
            <form action="proses-client.php" method="POST" enctype="multipart/form-data">
                <?php if($msg != '') {
                    echo $alert;
                }             
                ?>
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Client</span>
                    <button type="submit" name="simpan" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    <button type="reset" name="reset" class="btn btn-danger float-end me-2"><i class="fa-solid fa-xmark"></i> Reset</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                        <div class="mb-3 row">
                            <label for="idc" class="col-sm-2 col-form-label">ID Client</label>
                            <label for="idc" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-9" style="margin-left:-50px;">
                                <input type="text"name="idc"tittle="Masukkan ID Client"  class="form-control-plaintext border-bottom ps-2" required id="idc">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <label for="idc" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-9" style="margin-left:-50px;">
                                <input type="text"name="nama" required class="form-control border-0 border-bottom ps-2" id="nama">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="telpon" class="col-sm-2 col-form-label">No HP</label>
                            <label for="nama" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-9" style="margin-left:-50px;">
                                <input type="tel"name="telpon"pattern="[0-9]{5,}" tittle = "12 angka" required class="form-control border-0 border-bottom ps-2">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <label for="nama" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-9" style="margin-left:-50px;">
                                 <input type="text"name="email" required class="form-control border-0 border-bottom ps-2" id="email">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <label for="nama" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-9" style="margin-left:-50px;">
                               <textarea name="alamat" id="alamat" cols="30" rows="3" placeholder="Alamat Client" class="form-control" ></textarea>
                            </div>
                        </div>
                       </div>
                       
                    </div>
                </div>
            </div>
        </form>