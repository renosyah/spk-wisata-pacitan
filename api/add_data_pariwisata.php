<?php

include("header.php");

include("../model/data_pariwisata.php");
include("../model/db.php");

$one = new data_pariwisata();
$one->nama =  $_POST['nama'];
$one->lokasi =  $_POST['lokasi'];
$one->kategori =  $_POST['kategori'];
$one->jarak =  $_POST['jarak'];
$one->tiket_masuk =  $_POST['iket_masuk'];
$one->fasilitas =  $_POST['fasilitas'];
$one->umur =  $_POST['umur'];

$result = $one->add(get_connection("../config.ini"));
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>