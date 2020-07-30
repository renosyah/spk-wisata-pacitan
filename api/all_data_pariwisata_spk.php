<?php

// this spk for non-goa
require_once("header.php");
require_once("../model/data_pariwisata.php");
require_once("../model/spk_saw.php");
require_once("../model/db.php");

$kategori_id = $_GET['kategori_id'];
$fasilitas_id = $_GET['fasilitas_id'];

$min_tiket_masuk = $_GET['min_tiket_masuk'];
$min_jarak = $_GET['min_jarak'];

$max_tiket_masuk = $_GET['max_tiket_masuk'];
$max_jarak = $_GET['max_jarak'];

$all = new data_pariwisata();
$result = $all->all(get_connection("../config.ini"),
    $kategori_id,$fasilitas_id,
    $min_tiket_masuk,$min_jarak,
    $max_tiket_masuk,$max_jarak,
    0,0
);

$bobot = new kriteria();
$bobot->jarak = 0.40;
$bobot->tiket_masuk = 0.25;
$bobot->jumlah_fasilitas = 0.35;
$bobot->pakai_umur = false;


$MinMax = $bobot->getMinMax($result->data);
$normalisasi = $bobot->normalisasi($result->data,$MinMax);
$hitungPeringkat = $bobot->hitungPeringkat($result->data);
usort($hitungPeringkat, function($a, $b) {return strcmp($b->total, $a->total);});

$result->data =  $hitungPeringkat;

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>