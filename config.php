<?php

$koneksi = mysqli_connect("localhost", "root", "", "test");


$main_url = "http://localhost/jasadesign/";


function uploadimg($url){
    $namafile = $_FILES['image']['name'];
    $ukuran = $_FILES['image']['size'];
    $error = $_FILES['image']['error']; 
    $tmp = $_FILES['image']['tmp_name'];



// cek file yang di upload
$validExtension = ['jpg', 'jpeg', 'png'];
$fileExtension = explode('.', $namafile);
$fileExtension = strtolower(end($fileExtension));
if (!in_array($fileExtension, $validExtension)) {
    header("location:" . $url.'?msg=notimage');
    die;
}
// generate nama file gambar
$namafilebaru = rand(10,1000) . '-' . $namafile;

// upload gambar
move_uploaded_file($tmp, "../asset/image/". $namafilebaru);
return $namafilebaru;

}
