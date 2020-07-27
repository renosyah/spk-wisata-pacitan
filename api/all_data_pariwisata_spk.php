<?php

require_once("header.php");
require_once("../model/data_pariwisata.php");
require_once("../model/spk_saw.php");
require_once("../model/db.php");
require_once("../model/list_query.php");

$kategori_id = $_GET['kategori_id'];
$fasilitas_id = $_GET['fasilitas_id'];
$tiket_masuk= $_GET['tiket_masuk'];
$jarak = $_GET['jarak'];
$umur = $_GET['umur'];

$all = new data_pariwisata();
$result = $all->allToCriteria(get_connection("../config.ini"),$kategori_id,$fasilitas_id,$tiket_masuk,$jarak,$umur);

$kr = new kriteria();
$kr->jarak = (int)$jarak;
$kr->umur = (int)$umur;
$kr->tiket_masuk = (int)$tiket_masuk;


$MinMax = $kr->getMinMax($result->data);
$normalisasi = $kr->normalisasi($result->data,$MinMax);
$hitungPeringkat =  $kr->hitungPeringkat($result->data);
usort($hitungPeringkat, function($a, $b) {return strcmp($b->total, $a->total);});


// kategori goa dan umur 0, ngga akan dikasih hasil
$result->data = ($kategori_id == 1 && $umur == 0) ? null : $hitungPeringkat;

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>