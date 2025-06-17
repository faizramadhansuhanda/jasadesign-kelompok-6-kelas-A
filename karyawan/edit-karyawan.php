<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit;
}

require_once "../config.php";
$tittle = "Update Karyawan - NARSIS ROOM";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$idk = $_GET['idk'];
$karyawan = mysqli_query($koneksi, "SELECT * FROM karyawan WHERE idk='$idk'");
$data = mysqli_fetch_array($karyawan);



?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Update Karyawan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item "><a href="karyawan.php">Karyawan</a></li>
                <li class="breadcrumb-item active">Update Karyawan</li>
            </ol>
            <form action="proses-karyawan.php" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Update Karyawan</span>
                        <button type="submit" name="update" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="mb-3 row">
                                    <label for="idk" class="col-sm-2 col-form-label">IDK</label>
                                    <label for="idk" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <input type="text" name="idk" readonly class="form-control-plaintext border-bottom ps-2" id="idk" value="<?= $idk  ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                    <label for="idk" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <input type="text" name="nama" required class="form-control border-0 border-bottom ps-2" id="nama" value="<?= $data['nama'] ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="posisi" class="col-sm-2 col-form-label">Posisi</label>
                                    <label for="idk" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <select name="posisi" id="posisi" class="form-select border-0 border-bottom" required>
                                            <?php
                                            $posisi = ["Designer", "Copy Writer", "Ediot", "Photographer"];
                                            foreach ($posisi as $pss) {
                                                if ($data['posisi'] == $pss) { ?>
                                                    <option value="<?= $pss; ?>" selected><?= $pss; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $pss; ?>"><?= $pss; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="Alamat" class="col-sm-2 col-form-label">Alamat</label>
                                    <label for="nama" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left:-50px;">
                                        <textarea name="alamat" id="alamat" cols="30" rows="3" placeholder="Alamat Karyawan" class="form-control" required><?= $data ['alamat']?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-center px-5">
                                <input type="hidden" name="fotoLama" value="<?= htmlspecialchars(trim($data['foto'])); ?>">
                                <img src="../asset/image/<?= $data['foto']?>" alt="" class="mb-3 rounded-circle" width="40%">
                                <input type="file" name="image" class="form-control form-control-sm">
                                <small class="text-secondary">Pilih foto PNG, JPG, atau JPEG</small>
                                <div>
                                    <small class="text-secondary">Width = Height</small>
                                </div>
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