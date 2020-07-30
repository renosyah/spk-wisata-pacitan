<?php

// akan mengimport file header.php untuk digunakan
require_once("header.php");

// akan mengimport file data_pariwisata.php untuk digunakan
require_once("../model/data_pariwisata.php");

// akan mengimport file spk_saw.php" untuk digunakan
require_once("../model/spk_saw.php");

// akan mengimport file db.phpr untuk digunakan
require_once("../model/db.php");

// mengambil nilai kategori_id dari parameter dengan nama kategori_id
$kategori_id = $_GET['kategori_id'];

// mengambil nilai fasilitas_id dari parameter dengan nama fasilitas_id
$fasilitas_id = $_GET['fasilitas_id'];

// mengambil nilai min_tiket_masuk dari parameter dengan nama min_tiket_masuk
$min_tiket_masuk = $_GET['min_tiket_masuk'];

// mengambil nilai max_tiket_masuk dari parameter dengan nama max_tiket_masuk
$max_tiket_masuk = $_GET['max_tiket_masuk'];

// mengambil nilai min_jarak dari parameter dengan nama min_jarak
$min_jarak = $_GET['min_jarak'];

// mengambil nilai max_jarak dari parameter dengan nama max_jarak
$max_jarak = $_GET['max_jarak'];


// membuat instance data_pariwisata
$all = new data_pariwisata();

// yang akan memanggil fungsi query semua data
// dengan semua parameter request yang dibutuhkan
$result = $all->all(get_connection("../config.ini"),
    $kategori_id,$fasilitas_id,
    $min_tiket_masuk,$min_jarak,
    $max_tiket_masuk,$max_jarak,
    0,0
);

// membuat instance bobot untuk kriteria
$bobot = new kriteria();

// menentukan bobot jarak
$bobot->jarak = 0.40;

// menentukan bobot tiket_masuk
$bobot->tiket_masuk = 0.25;

// menentukan bobot jumlah_fasilitas
$bobot->jumlah_fasilitas = 0.35;

// menentukan apakah bobot umur ingin digunakan
// dan untuk yg bukan goa di set false
$bobot->pakai_umur = false;

// memanggil fungsi untuk mencari : 
// nilai terkecil (untuk cost) dan 
// nilai terbesar (untuk benefit)
$MinMax = $bobot->getMinMax($result->data);

// memanggil fungsi untuk menormalisasi matrix
$normalisasi = $bobot->normalisasi($result->data,$MinMax);

// memanggil fungsi menghitung total nilai peringkat
$hitungPeringkat = $bobot->hitungPeringkat($result->data);

// sortir array dari data terbesar ke terkecil (DESC)
usort($hitungPeringkat, function($a, $b) {return strcmp($b->total, $a->total);});

// letakan kembali sebagai hasil 
$result->data =  $hitungPeringkat;

// tampilkan respon sebagai json
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>