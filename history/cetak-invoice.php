<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use Mpdf\Mpdf;
use Mpdf\MpdfException;

// PERBAIKAN 1: Ambil parameter 'invoice' dari URL
if (!isset($_GET['invoice']) || empty($_GET['invoice'])) {
    die("Error: Kode Invoice tidak valid atau tidak ditemukan di URL.");
}
$kode_invoice = $_GET['invoice'];

// Query untuk mengambil data pesanan utama, sudah benar
$sql_pesanan = "SELECT p.*, c.nama as nama_client, c.alamat as alamat_client, c.telpon as telpon_client, k.nama as nama_karyawan FROM pesanan p LEFT JOIN client c ON p.id_client = c.idc LEFT JOIN karyawan k ON p.id_karyawan = k.idk WHERE p.kode_invoice = ?";
$stmt_pesanan = mysqli_prepare($koneksi, $sql_pesanan);
if (!$stmt_pesanan) {
    die("Error SQL (prepare pesanan): " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_pesanan, 's', $kode_invoice);
mysqli_stmt_execute($stmt_pesanan);
$result_pesanan = mysqli_stmt_get_result($stmt_pesanan);
$data_pesanan = mysqli_fetch_assoc($result_pesanan);

if (!$data_pesanan) {
    die("Error: Data pesanan untuk invoice " . htmlspecialchars($kode_invoice) . " tidak ditemukan.");
}
// Ambil id_pesanan (integer) untuk digunakan mencari rincian
$id_pesanan = $data_pesanan['id_pesanan'];

// PERBAIKAN 2: Query untuk mengambil rincian pesanan menggunakan id_pesanan (integer)
$sql_rincian = "SELECT r.*, l.nama_layanan FROM rincian r LEFT JOIN layanan l ON r.id_layanan = l.id_layanan WHERE r.id_pesanan = ?";
$stmt_rincian = mysqli_prepare($koneksi, $sql_rincian);
if (!$stmt_rincian) {
    die("Error SQL (prepare rincian): " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt_rincian, 'i', $id_pesanan); // 'i' untuk integer
mysqli_stmt_execute($stmt_rincian);
$result_rincian = mysqli_stmt_get_result($stmt_rincian);

// Membangun string HTML untuk PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Invoice ' . htmlspecialchars($data_pesanan['kode_invoice']) . '</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .container { padding: 10px 0; }
        .header, .footer { text-align: center; }
        .header h1 { margin: 0; font-size: 24px;}
        .invoice-details { margin-top: 20px; margin-bottom: 20px; }
        .invoice-details table { width: 100%; border-collapse: collapse; }
        .invoice-details td { padding: 5px; vertical-align: top;}
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; }
        .items-table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NARSIS ROOM</h1>
            <p>Jl. Kantor Pengelola Science Techno Park Unand, Limau Manis, Kec. Pauh, Kota Padang, Sumatera Barat 25163</p>
            <p> Telp: 0822-8811-5513 </p>
            <hr>
        </div>
        <h2>INVOICE</h2>
        <div class="invoice-details">
            <table>
                <tr>
                    <td width="60%">
                        <strong>No. Invoice:</strong> ' . htmlspecialchars($data_pesanan['kode_invoice']) . '<br>
                        <strong>Tanggal:</strong> ' . date('d F Y', strtotime($data_pesanan['tanggal_pesanan'])) . '<br>
                        <strong>Karyawan:</strong> ' . htmlspecialchars($data_pesanan['nama_karyawan']) . '
                    </td>
                    <td width="40%">
                        <strong>Kepada Yth:</strong><br>
                        ' . htmlspecialchars($data_pesanan['nama_client']) . '<br>
                        ' . htmlspecialchars($data_pesanan['alamat_client']) . '<br>
                        ' . htmlspecialchars($data_pesanan['telpon_client']) . '
                    </td>
                </tr>
            </table>
        </div>
        <table class="items-table">
            <thead>
                <tr>
                    <th align="center">No.</th>
                    <th>Nama Layanan</th>
                    <th align="right">Harga</th>
                    <th align="center">Jumlah</th>
                    <th align="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>';
$no = 1;
$total_semua = 0;
while ($item = mysqli_fetch_assoc($result_rincian)) {
    $html .= '
                <tr>
                    <td align="center">' . $no++ . '</td>
                    <td>' . htmlspecialchars($item['nama_layanan']) . '</td>
                    <td align="right">Rp ' . number_format($item['harga'], 0, ',', '.') . '</td>
                    <td align="center">' . htmlspecialchars($item['jumlah']) . '</td>
                    <td align="right">Rp ' . number_format($item['subtotal'], 0, ',', '.') . '</td>
                </tr>';
    $total_semua += $item['subtotal'];
}
$html .= '
            </tbody>
        </table>
        
        <table style="width: 100%; margin-top: 30px;">
            <tr>
                <td style="width: 60%; vertical-align: bottom;">
                    <strong>Informasi Pembayaran:</strong><br>
                    No.Rek : 71000220634906<br>
                    Bank Nagari Syariah.<br>
                    An. Muhammad Ghalib
                </td>
                <td style="width: 40%; text-align: right; vertical-align: middle;">
                    <h3 style="margin: 0;">Total Pembayaran:</h3>
                    <h2 style="margin: 0;">Rp ' . number_format($total_semua, 0, ',', '.') . '</h2>
                </td>
            </tr>
        </table>

        <div class="footer" style="margin-top: 50px;">
            <p>Terima kasih atas kepercayaan Anda.</p>
        </div>
    </div>
</body>
</html>';

// Proses Generate PDF
try {
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('invoice-' . $data_pesanan['kode_invoice'] . '.pdf', 'I');
} catch (MpdfException $e) {
    die("Error mPDF: " . $e->getMessage());
}

exit;
?>