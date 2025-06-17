<?php 
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit();
}
require_once "../config.php";


if (isset($_POST['simpan'])) {
    // Ambil value dari form
    // PERBAIKAN: Menggunakan nama variabel dan POST key 'idc'
    $idc    = trim(htmlspecialchars($_POST['idc']));
    $nama   = trim(htmlspecialchars($_POST['nama']));
    $telpon = trim(htmlspecialchars($_POST['telpon']));
    $email  = trim(htmlspecialchars($_POST["email"]));
    $alamat = trim(htmlspecialchars($_POST["alamat"]));

    // Cek apakah IDC sudah ada menggunakan prepared statement
    // PERBAIKAN: Mengecek di kolom 'idc'
    $stmt_cek = mysqli_prepare($koneksi, "SELECT idc FROM client WHERE idc = ?");
    mysqli_stmt_bind_param($stmt_cek, 's', $idc);
    mysqli_stmt_execute($stmt_cek);
    mysqli_stmt_store_result($stmt_cek);

    if (mysqli_stmt_num_rows($stmt_cek) > 0) {
        header("location:add-client.php?msg=cancel"); // 'cancel' berarti ID sudah ada
        exit();
    }
    mysqli_stmt_close($stmt_cek);

    // Insert data dengan menyebutkan nama kolom dan prepared statement
    // PERBAIKAN: Memasukkan data ke kolom 'idc'
    $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO client (idc, nama, telpon, email, alamat) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_insert, 'sssss', $idc, $nama, $telpon, $email, $alamat);
    
    if (mysqli_stmt_execute($stmt_insert)) {
        header("location:add-client.php?msg=added");
    } else {
        header("location:add-client.php?msg=error");
    }
    mysqli_stmt_close($stmt_insert);
    exit();
} 


else if (isset($_POST['update'])) {
    // PERBAIKAN: Menggunakan 'idc' sebagai Primary Key untuk WHERE clause
    $idc    = $_POST['idc']; 
    $nama   = trim(htmlspecialchars($_POST['nama']));
    $telpon = trim(htmlspecialchars($_POST['telpon']));
    $email  = trim(htmlspecialchars($_POST["email"]));
    $alamat = trim(htmlspecialchars($_POST["alamat"]));

    // Karena 'idc' adalah Primary Key, kita tidak perlu mengecek duplikat saat update.
    // Cukup update data lainnya berdasarkan 'idc' yang ada.
    // PERBAIKAN: Query UPDATE menggunakan WHERE idc = ?
    $stmt_update = mysqli_prepare($koneksi, "UPDATE client SET nama = ?, telpon = ?, email = ?, alamat = ? WHERE idc = ?");
    // PERBAIKAN: Binding parameter disesuaikan (4 string untuk SET, 1 string untuk WHERE)
    mysqli_stmt_bind_param($stmt_update, 'sssss', $nama, $telpon, $email, $alamat, $idc);

    if(mysqli_stmt_execute($stmt_update)){
        header("location:client.php?msg=updated");
    } else {
        header("location:client.php?msg=error");
    }
    mysqli_stmt_close($stmt_update);
    exit();
}
?>