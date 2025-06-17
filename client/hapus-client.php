<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";

$idc = $_GET['idc'] ?? null;

if ($idc) {
    // Gunakan Prepared Statement untuk keamanan dan keandalan
    $stmt = mysqli_prepare($koneksi, "DELETE FROM client WHERE idc = ?");
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $idc);

        // Coba eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            // Setelah eksekusi, cek apakah ada baris yang terpengaruh/terhapus
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // Jika ada baris yang terhapus, ini baru benar-benar sukses
                header("location:client.php?msg=deleted");
            } else {
                // Jika tidak ada baris yang terhapus, berarti ID tidak ditemukan
                header("location:client.php?msg=notfound");
            }
        } else {
            // Jika eksekusi gagal karena error lain (misal: permission)
            header("location:client.php?msg=error");
        }
        mysqli_stmt_close($stmt);
    } else {
        // Jika query gagal dipersiapkan (misal: error sintaks)
        header("location:client.php?msg=error");
    }
} else {
    // Jika tidak ada parameter 'idc' di URL
    header("location:client.php?msg=error");
}
exit();

?>