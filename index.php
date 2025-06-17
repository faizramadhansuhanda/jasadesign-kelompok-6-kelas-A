 <?php

    session_start();
    if (!isset($_SESSION["ssLogin"])) {
        header("location:auth/login.php");
        exit;
    }

    require_once "config.php";


    // BAGIAN 1: QUERY UNTUK KARTU STATISTIK

    // Query untuk Total Pendapatan
    // Kita menjumlahkan subtotal dari SEMUA pesanan yang pernah ada
    $query_pendapatan = "SELECT SUM(subtotal) as total_pendapatan FROM rincian";
    $result_pendapatan = mysqli_query($koneksi, $query_pendapatan);
    $data_pendapatan = mysqli_fetch_assoc($result_pendapatan);
    $total_pendapatan = $data_pendapatan['total_pendapatan'];

    // Query untuk Total Order
    $query_order = "SELECT COUNT(*) as total_order FROM pesanan";
    $result_order = mysqli_query($koneksi, $query_order);
    $data_order = mysqli_fetch_assoc($result_order);
    $total_order = $data_order['total_order'];

    // Query untuk Jumlah Karyawan
    $query_karyawan = "SELECT COUNT(*) as total_karyawan FROM karyawan";
    $result_karyawan = mysqli_query($koneksi, $query_karyawan);
    $data_karyawan = mysqli_fetch_assoc($result_karyawan);
    $total_karyawan = $data_karyawan['total_karyawan'];


    // BAGIAN 2: QUERY UNTUK GRAFIK PENDAPATAN PER BULAN
    // Siapkan array untuk 12 bulan dengan nilai awal 0
    $pendapatan_per_bulan = array_fill(1, 12, 0);
    $bulan_labels = [];

    // Membuat label bulan dalam Bahasa Indonesia
    for ($i = 1; $i <= 12; $i++) {
        $bulan_labels[] = date('F', mktime(0, 0, 0, $i, 1));
    }

    // Ambil data pendapatan per bulan untuk TAHUN INI
    $tahun_sekarang = date('Y');
    $query_grafik = "SELECT MONTH(p.tanggal_pesanan) as bulan, SUM(r.subtotal) as pendapatan_bulanan 
                 FROM pesanan p
                 JOIN rincian r ON p.kode_invoice = r.id_pesanan
                 WHERE YEAR(p.tanggal_pesanan) = '$tahun_sekarang'
                 GROUP BY MONTH(p.tanggal_pesanan)
                 ORDER BY bulan ASC";

    $result_grafik = mysqli_query($koneksi, $query_grafik);
    while ($data_grafik = mysqli_fetch_assoc($result_grafik)) {
        $bulan = (int)$data_grafik['bulan'];
        $pendapatan_per_bulan[$bulan] = (int)$data_grafik['pendapatan_bulanan'];
    }

    // Konversi data ke format yang bisa dibaca oleh JavaScript Chart
    // Kita hanya akan menampilkan 6 bulan terakhir untuk contoh, atau bisa 12 bulan
    $chart_labels_json = json_encode(array_slice($bulan_labels, 0, 6)); // Jan-Juni
    $chart_data_json = json_encode(array_slice(array_values($pendapatan_per_bulan), 0, 6)); // Data Jan-Juni


    $tittle = "Dashboard - Admin Narsis Room";
    require_once "template/header.php";
    require_once "template/navbar.php";
    require_once "template/sidebar.php";

    ?>

 <div id="layoutSidenav_content">
     <main>
         <div class="container-fluid px-4">
             <h1 class="mt-4">Dashboard Admin</h1>
             <ol class="breadcrumb mb-4">
                 <li class="breadcrumb-item active">Home</li>
             </ol>
             <div class="row">

                 <div class="col-xl-3 col-md-6 mb-4">
                     <div class="card border-left-primary shadow h-100 py-2">
                         <div class="card-body">
                             <div class="row no-gutters align-items-center">
                                 <div class="col mr-2">
                                     <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendapatan (All Time)</div>
                                     <div class="h5 mb-0 font-weight-bold text-gray-800"><?= 'Rp ' . number_format($total_pendapatan, 0, ',', '.') ?></div>
                                 </div>
                                 <div class="col-auto">
                                     <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="col-xl-3 col-md-6 mb-4">
                     <div class="card border-left-success shadow h-100 py-2">
                         <div class="card-body">
                             <div class="row no-gutters align-items-center">
                                 <div class="col mr-2">
                                     <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Order</div>
                                     <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_order ?> Pesanan</div>
                                 </div>
                                 <div class="col-auto">
                                     <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="col-xl-3 col-md-6 mb-4">
                     <div class="card border-left-info shadow h-100 py-2">
                         <div class="card-body">
                             <div class="row no-gutters align-items-center">
                                 <div class="col mr-2">
                                     <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Karyawan</div>
                                     <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_karyawan ?> Orang</div>
                                 </div>
                                 <div class="col-auto">
                                     <i class="fas fa-users fa-2x text-gray-300"></i>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="col-xl-3 col-md-6 mb-4">
                     <div class="card border-left-warning shadow h-100 py-2">
                         <div class="card-body">
                             <div class="row no-gutters align-items-center">
                                 <div class="col mr-2">
                                     <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Riwayat Pesanan</div>
                                     <a href="<?= $main_url ?>history/history.php" class="stretched-link">
                                         <div class="h5 mb-0 font-weight-bold text-gray-800">Lihat History</div>
                                     </a>
                                 </div>
                                 <div class="col-auto">
                                     <i class="fas fa-history fa-2x text-gray-300"></i>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </main>

     <?php
        require_once "template/footer.php";

        ?>