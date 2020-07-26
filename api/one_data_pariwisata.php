<?php

header("Content-Type: application/json; charset=UTF-8");
include("../model/data_pariwisata.php");
include("../model/db.php");

$one = new data_pariwisata();
$one->id = $_GET['id'];

$result = $one->one(get_connection("../config.ini"));
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>