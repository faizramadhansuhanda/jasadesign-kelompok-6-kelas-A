<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header ("location: ../auth/login.php");
    exit();
}


require_once "../config.php";

 if (isset($_POST['simpan'])) {
    $idk    = $_POST['idk'];
    $nama   = htmlspecialchars($_POST['nama']);
    $posisi  = $_POST["posisi"];
    $alamat = htmlspecialchars($_POST["alamat"]);
    $foto   = htmlspecialchars($_FILES['image']['name']);


    if ($foto != null) {
        $url = "add-karyawan.php";
        $foto = uploadimg($url);

    } else {
        $foto = 'default.png';
    }

    mysqli_query($koneksi, "INSERT INTO karyawan VALUES('$idk', '$nama', '$alamat', '$posisi', '$foto')");
    
    echo "<script>
            alert('Data karyawan berhasil disimpan');
            document.location.href = 'add-karyawan.php';
        </script>";
    return;
 } else if (isset($_POST['update'])) {
    $idk = $_POST['idk'];
    $nama = htmlspecialchars($_POST['nama']);
    $posisi = $_POST['posisi'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $foto = htmlspecialchars(trim($_POST['fotoLama']));

    if ($_FILES['image']['error'] === 4) {
        $fotoKaryawan = $foto;
    } else {
        $url = "karyawan.php";
        $fotoKaryawan = uploadimg($url);
        if ($foto != 'default.png') {
            @unlink('../asset/image/'. $foto);

        }
    }


     mysqli_query ($koneksi, "UPDATE karyawan SET
                                nama = '$nama',
                                posisi = '$posisi',
                                alamat = '$alamat',
                                foto = '$fotoKaryawan'
                                WHERE idk = '$idk'
                                ");   
                                
        echo "<script> 
            alert('Data Karyawan berhasil di Update!');
            document.location.href='karyawan.php';
        </script>";
        return;   
 }

?>