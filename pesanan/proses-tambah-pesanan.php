<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['simpan'])) {
        $id_client = $_POST['id_client'];
        $id_karyawan = $_POST['id_karyawan'];
        $tanggal_pesanan = $_POST['tanggal_pesanan'];

        $id_layanan_array = $_POST['id_layanan'];
        $harga_array = $_POST['harga'];
        $jumlah_array = $_POST['jumlah'];
        $subtotal_array = $_POST['subtotal'];

        mysqli_autocommit($koneksi, FALSE);
        $semua_query_sukses = true;

        $tanggal_kode = date('Ymd', strtotime($tanggal_pesanan));
        $prefix = 'NR-' . $tanggal_kode . '-';
        
        $query_latest_invoice = mysqli_query($koneksi, "SELECT kode_invoice FROM pesanan WHERE kode_invoice LIKE '$prefix%' ORDER BY kode_invoice DESC LIMIT 1");
        
        $no_urut = 1;
        if (mysqli_num_rows($query_latest_invoice) > 0) {
            $last_kode = mysqli_fetch_assoc($query_latest_invoice)['kode_invoice'];
            $last_urut = (int)substr($last_kode, -3);
            $no_urut = $last_urut + 1;
        }
        $kode_invoice = $prefix . sprintf('%03d', $no_urut);

        $stmt_pesanan = mysqli_prepare($koneksi, "INSERT INTO pesanan (kode_invoice, id_client, id_karyawan, tanggal_pesanan) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_pesanan, 'ssss', $kode_invoice, $id_client, $id_karyawan, $tanggal_pesanan);
        
        if (!mysqli_stmt_execute($stmt_pesanan)) {
            $semua_query_sukses = false;
        }
        $id_pesanan_baru = mysqli_insert_id($koneksi);
        mysqli_stmt_close($stmt_pesanan);

        if ($semua_query_sukses) {
            $stmt_rincian = mysqli_prepare($koneksi, "INSERT INTO rincian (id_pesanan, id_layanan, harga, jumlah, subtotal) VALUES (?, ?, ?, ?, ?)");
            foreach ($id_layanan_array as $key => $id_layanan) {
                mysqli_stmt_bind_param($stmt_rincian, 'isdsd', $id_pesanan_baru, $id_layanan_array[$key], $harga_array[$key], $jumlah_array[$key], $subtotal_array[$key]);
                if (!mysqli_stmt_execute($stmt_rincian)) {
                    $semua_query_sukses = false;
                    break; 
                }
            }
            mysqli_stmt_close($stmt_rincian);
        }

        if ($semua_query_sukses) {
            mysqli_commit($koneksi);
            header("Location: ../history/history.php?msg=added");
            exit();
        } else {
            mysqli_rollback($koneksi);
            header("Location: pesanan.php?msg=aborted");
            exit();
        }

    } else if (isset($_POST['action_update'])) {
    
    $id_pesanan = $_POST['id_pesanan'];
    $kode_invoice = $_POST['kode_invoice'];
    $tanggal_pesanan = $_POST['tanggal_pesanan'];
    $id_client = $_POST['id_client'];
    $id_karyawan = $_POST['id_karyawan'];

    $id_layanan_arr = $_POST['id_layanan'];
    $harga_arr = $_POST['harga'];
    $jumlah_arr = $_POST['jumlah'];
    $subtotal_arr = $_POST['subtotal'];

    mysqli_autocommit($koneksi, FALSE);
    $semua_query_sukses = true;

    $stmt_update = mysqli_prepare($koneksi, "UPDATE pesanan SET tanggal_pesanan = ?, id_client = ?, id_karyawan = ? WHERE id_pesanan = ?");
    mysqli_stmt_bind_param($stmt_update, "sssi", $tanggal_pesanan, $id_client, $id_karyawan, $id_pesanan);
    if (!mysqli_stmt_execute($stmt_update)) {
        $semua_query_sukses = false;
        error_log("Gagal update tabel pesanan: " . mysqli_stmt_error($stmt_update));
    }
    mysqli_stmt_close($stmt_update);

    if ($semua_query_sukses) {
        $stmt_delete = mysqli_prepare($koneksi, "DELETE FROM rincian WHERE id_pesanan = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $id_pesanan);
        if (!mysqli_stmt_execute($stmt_delete)) {
            $semua_query_sukses = false;
            error_log("Gagal hapus tabel rincian: " . mysqli_stmt_error($stmt_delete));
        }
        mysqli_stmt_close($stmt_delete);
    }

    if ($semua_query_sukses) {
        $stmt_insert_detail = mysqli_prepare($koneksi, "INSERT INTO rincian (id_pesanan, id_layanan, harga, jumlah, subtotal) VALUES (?, ?, ?, ?, ?)");
        for ($i = 0; $i < count($id_layanan_arr); $i++) {
            mysqli_stmt_bind_param($stmt_insert_detail, "isdsd", $id_pesanan, $id_layanan_arr[$i], $harga_arr[$i], $jumlah_arr[$i], $subtotal_arr[$i]);
            if (!mysqli_stmt_execute($stmt_insert_detail)) {
                $semua_query_sukses = false;
                error_log("Gagal insert ulang tabel rincian: " . mysqli_stmt_error($stmt_insert_detail));
                break;
            }
        }
        mysqli_stmt_close($stmt_insert_detail);
    }

    if ($semua_query_sukses) {
        mysqli_commit($koneksi);
        header("Location: ../history/history.php?msg=updated");
        exit();
    } else {
        mysqli_rollback($koneksi);
        header("Location: edit-pesanan.php?id=" . urlencode($id_pesanan) . "&msg=update_failed");
        exit();
    }
    }
}