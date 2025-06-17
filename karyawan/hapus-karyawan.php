<?php

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit();
}

require_once "../config.php";

$id = $_GET['idk'];
$foto = $_GET['foto'];

mysqli_query($koneksi, "DELETE FROM karyawan WHERE idk ='$id'");
if ($foto != 'default.png') {
    unlink('../asset/image/'. $foto);

}
echo "<script>
    alert('Data Karyawan berhasil dihapus.');
    document.location.href='karyawan.php';

</script>";
return;

?>