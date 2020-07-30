<?php

// this spk for goa
require_once("header.php");
require_once("../model/data_pariwisata.php");
require_once("../model/spk_saw.php");
require_once("../model/db.php");

$kategori_id = 1;
$fasilitas_id = $_GET['fasilitas_id'];

$min_tiket_masuk = $_GET['min_tiket_masuk'];
$min_jarak = $_GET['min_jarak'];

$max_tiket_masuk = $_GET['max_tiket_masuk'];
$max_jarak = $_GET['max_jarak'];

$min_umur= $_GET['min_umur'];
$max_umur= $_GET['max_umur'];

$all = new data_pariwisata();
$result = $all->all(get_connection("../config.ini"),
    $kategori_id,$fasilitas_id,
    $min_tiket_masuk,$min_jarak,
    $max_tiket_masuk,$max_jarak,
    $min_umur,$max_umur
);

$bobot = new kriteria();
$bobot->jarak = 0.25;
$bobot->umur = 0.25;
$bobot->tiket_masuk = 0.25;
$bobot->jumlah_fasilitas = 0.25;
$bobot->pakai_umur = true;


$MinMax = $bobot->getMinMax($result->data);
$normalisasi = $bobot->normalisasi($result->data,$MinMax);
$hitungPeringkat = $bobot->hitungPeringkat($result->data);
usort($hitungPeringkat, function($a, $b) {return strcmp($b->total, $a->total);});
$result->data = $hitungPeringkat;

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>