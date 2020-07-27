<?php

header("Content-Type: application/json; charset=UTF-8");
include("../model/tiket_masuk.php");
include("../model/db.php");
include("../model/list_query.php");

$all = new tiket_masuk();
$result = $all->all(get_connection("../config.ini"));
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>