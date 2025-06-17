<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$tittle = "Karyawan - NARSIS ROOM";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Karyawan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Karyawan</li>
            </ol>
            <div class="card">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-list"></i></i> Data Karyawan</span>
                    <a href="<?= $main_url ?>karyawan/add-karyawan.php" class="btn btn-sm btn-primary float-end"><i class="fa-solid fa-plus"></i> Tambah Karyawan</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">
                                    <center>Foto</center>
                                </th>
                                <th scope="col">
                                    <center>ID Karyawan</center>
                                </th>
                                <th scope="col">
                                    <center>Nama</center>
                                </th>
                                <th scope="col">
                                    <center>Posisi</center>
                                </th>
                                <th scope="col">
                                    <center>Alamat</center>
                                </th>
                                <th scope="col">
                                    <center>Operasi</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$no = 1;
$queryKaryawan = mysqli_query($koneksi,"SELECT * FROM karyawan");
while ($data = mysqli_fetch_array($queryKaryawan)) { ?>
                            <tr>
                                <th scope="row"><?=$no++ ?></th>
                                <td align="center"><img src="../asset/image/<?= $data['foto']?> " class="rounded-circle" alt="foto karyawan" width="60px"></td>
                                <td><?= $data['idk']?></td>
                                <td><?= $data['nama']?></td>
                                <td><?= $data['posisi']?></td>
                                <td> <?= $data['alamat']?></td>
                                <td align="center">
                                    <a href="edit-karyawan.php?idk=<?= $data['idk']?>" class="btn btn-sm btn-warning" title="Update Karyawan"><i class="fa-solid fa-pen"></i></a>
                                    <a href="hapus-karyawan.php?idk=<?= $data['idk']?>&foto=<?= $data['foto']?>" class="btn btn-sm btn-danger" title="Hapus Karyawan" onclick="return confirm('Anda yakin menghapus data ini?')"><i class="fa-solid fa-trash"></i> </a>
                                </td>
                            </tr>
            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php

    require_once "../template/footer.php";
    ?>