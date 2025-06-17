          <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Home</div>
                            <a class="nav-link" href="<?= $main_url?>index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <hr class="mb-0">
                            <div class="sb-sidenav-menu-heading">Admin</div>
                            <a class="nav-link" href="<?= $main_url?>user/add-user.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                                User
                            </a>
                           
                            <hr class="mb-0">
                            <div class="sb-sidenav-menu-heading">Data</div>
                            <a class="nav-link" href="<?= $main_url?>karyawan/karyawan.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                                Karyawan
                            </a>
                            <a class="nav-link" href="<?= $main_url?>client/client.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                                Client
                            </a>
                            <a class="nav-link" href="<?= $main_url?>history/history.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                                History
                            </a>
                            <a class="nav-link" href="<?= $main_url?>pesanan/pesanan.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard"></i></i></div>
                                Pesanan
                            </a>
                            <hr class="mb-0">
                        </div>
                    </div>
                    <div class="sb-sidenav-footer border">
                        <div class="small">Logged in as:</div>
                        <?= "Admin" ?>
                    </div>
                </nav>
            </div>