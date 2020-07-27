<?php

header("Content-Type: application/json; charset=UTF-8");
include("../model/data_pariwisata.php");
include("../model/db.php");

$one = new data_pariwisata();
$one->nama = $_GET['nama'];

$result = $one->oneByNama(get_connection("../config.ini"));
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>