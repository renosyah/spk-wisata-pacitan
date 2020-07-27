<?php

header("Content-Type: application/json; charset=UTF-8");
include("../model/data_pariwisata.php");
include("../model/db.php");

$one = new data_pariwisata();
$one->nama = $_GET['nama'];
$one->kategori = $_GET['kategori'];
$one->fasilitas = $_GET['fasilitas'];
$one->tiket_masuk = $_GET['tiket_masuk'];
$one->jarak = $_GET['jarak'];
$one->umur = $_GET['umur'];

$result = $one->oneByData(get_connection("../config.ini"));
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>