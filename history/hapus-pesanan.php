<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";

$id_pesanan = $_GET['id'] ?? null;

if ($id_pesanan) {
    mysqli_autocommit($koneksi, FALSE);
    $semua_query_sukses = true;

    $stmt_rincian = mysqli_prepare($koneksi, "DELETE FROM rincian WHERE id_pesanan = ?");
    if ($stmt_rincian) {
        mysqli_stmt_bind_param($stmt_rincian, 'i', $id_pesanan);
        if (!mysqli_stmt_execute($stmt_rincian)) {
            $semua_query_sukses = false;
        }
        mysqli_stmt_close($stmt_rincian);
    } else {
        $semua_query_sukses = false;
    }

    if ($semua_query_sukses) {
        $stmt_pesanan = mysqli_prepare($koneksi, "DELETE FROM pesanan WHERE id_pesanan = ?");
        if ($stmt_pesanan) {
            mysqli_stmt_bind_param($stmt_pesanan, 'i', $id_pesanan);
            if (!mysqli_stmt_execute($stmt_pesanan)) {
                $semua_query_sukses = false;
            }
            mysqli_stmt_close($stmt_pesanan);
        } else {
            $semua_query_sukses = false;
        }
    }

    if ($semua_query_sukses) {
        mysqli_commit($koneksi);
        header("Location: history.php?msg=deleted");
    } else {
        mysqli_rollback($koneksi);
        header("Location: history.php?msg=error");
    }
    exit();

} else {
    header("Location: history.php?msg=error");
    exit();
}
?>