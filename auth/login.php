<?php

session_start();

if (isset($_SESSION["ssLogin"])) {
    header("location:../index.php");
    exit;
}

require_once "../config.php";




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - NARSIS ROOM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="<?= $main_url ?? '' ?>asset/image/toga.png">

    <style>
        body {
            background-color: #0D1A26; /* Warna latar belakang gelap seperti gambar */
            color: #E0E0E0; /* Warna teks default terang */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Font yang clean */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-panel-wrapper {
            width: 100%;
            max-width: 400px; /* Sesuaikan lebar panel */
            padding: 20px;
        }

        .login-panel {
            background-color: #172734; /* Warna panel lebih gelap dari gambar */
            padding: 40px 30px;
            border-radius: 8px; /* Sedikit rounded corner */
            /* box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); Hilangkan shadow bawaan jika ada */
        }

        .panel-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .panel-header .icon {
            font-size: 3rem; /* Ukuran ikon kunci */
            color: #4FD1C5; /* Warna aksen teal/cyan */
            margin-bottom: 10px;
        }

        .panel-header h4 {
            color: #E0E0E0;
            font-weight: 600;
            letter-spacing: 1px;
            font-size: 1.5rem;
        }

        .form-group-custom {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group-custom label {
            display: block;
            color: #A0A0A0; /* Warna label sedikit lebih redup */
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .form-control-custom {
            background-color: transparent;
            border: none;
            border-bottom: 2px solid #4FD1C5; /* Garis bawah dengan warna aksen */
            color: #E0E0E0; /* Warna teks input */
            width: 100%;
            padding: 10px 0;
            font-size: 1rem;
            border-radius: 0; /* Pastikan tidak ada rounded corner dari bootstrap */
        }

        .form-control-custom:focus {
            background-color: transparent;
            color: #E0E0E0;
            border-bottom-color: #66FFED; /* Warna aksen lebih terang saat fokus */
            outline: none;
            box-shadow: none;
        }
        
        /* Hilangkan panah di input number jika ada, dan styling autocomplete browser */
        .form-control-custom::-webkit-outer-spin-button,
        .form-control-custom::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .form-control-custom[type=number] {
            -appearance: textfield;
        }
        .form-control-custom:-webkit-autofill,
        .form-control-custom:-webkit-autofill:hover, 
        .form-control-custom:-webkit-autofill:focus, 
        .form-control-custom:-webkit-autofill:active  {
            -webkit-text-fill-color: #E0E0E0 !important; /* Warna teks saat autofill */
            -webkit-box-shadow: 0 0 0px 1000px #172734 inset !important; /* Warna background saat autofill */
            transition: background-color 5000s ease-in-out 0s; /* Mencegah perubahan warna background oleh browser */
        }


        .btn-custom-login {
            background-color: #4FD1C5; /* Warna aksen untuk tombol */
            color: #101820; /* Warna teks tombol gelap agar kontras */
            border: none;
            padding: 12px 20px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px; /* Sedikit rounded corner */
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block; /* Agar bisa di-center dengan margin auto */
            width: auto; /* Lebar sesuai konten + padding */
            margin: 30px auto 0 auto; /* Margin atas, dan auto kiri-kanan untuk centering */
            min-width: 120px; /* Lebar minimum tombol */
            text-align: center;
        }

        .btn-custom-login:hover {
            background-color: #66FFED; /* Warna aksen lebih terang saat hover */
            color: #0D1A26;
        }
        
        /* Menghilangkan card-footer dari kode asli */
        .card-footer { 
            display: none;
        }

    </style>
</head>
<body> <div class="login-panel-wrapper">
        <div class="login-panel"> <div class="panel-header"> <div class="icon"><i class="fas fa-key"></i></div>
                <h4>LOGIN ADMIN</h4>
            </div>
            <div> <form action="proseslogin.php" method="POST">
                    <div class="form-group-custom">
                        <label for="username">Username</label>
                        <input class="form-control-custom" id="username" name="username" type="text" pattern="[A-Za-z0-9]{3,}" title="username" autocomplete="off" required />
                    </div>
                    <div class="form-group-custom">
                        <label for="inputPassword">Password</label>
                        <input class="form-control-custom" id="inputPassword" name="password" type="password" minlength="4" required />
                    </div>
                    <button type="submit" name="login" class="btn-custom-login">Login</button>
                </form>
            </div>
            </div>
    </div>

    </body>
</html>