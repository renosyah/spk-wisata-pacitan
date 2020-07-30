<?php

// akan mengimport file header.php untuk digunakan
include("header.php");

// akan mengimport file data_pariwisata.php untuk digunakan
include("../model/data_pariwisata.php");

// akan mengimport file db.phpr untuk digunakan
include("../model/db.php");

// membuat instance data_pariwisata
$one = new data_pariwisata();

// set id yang bilainya diambil dari 
// parameter request dengan nama id
$one->id = $_GET['id'];

// yang akan memanggil fungsi query semua data
// dengan semua parameter request yang dibutuhkan
$result = $one->one(get_connection("../config.ini"));

// tampilkan respon sebagai json
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>