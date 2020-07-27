<?php

include("header.php");
include("../model/data_pariwisata.php");
include("../model/spk_saw.php");
include("../model/db.php");
include("../model/list_query.php");

$category = $_GET['kategori'];
$facility = $_GET['fasilitas'];
$price = $_GET['tiket_masuk'];
$distance = $_GET['jarak'];
$age = $_GET['umur'];

$all = new data_pariwisata();
$result = $all->allGrouping(get_connection("../config.ini"),$category,$facility,$price,$distance,$age);

$criteria = new criteria();
$criteria->jarak = (int)$distance;
$criteria->umur = (int)$age;
$criteria->tiket_masuk = (int)$price;

$MinMax = $criteria->getMinMax($result->data);
$normalisasi = $criteria->normalisasi($result->data,$MinMax);
$hitungPeringkat =  $criteria->hitungPeringkat($result->data);
usort($hitungPeringkat, function($a, $b) {return strcmp($b->total, $a->total);});

$result->data = $hitungPeringkat;

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>