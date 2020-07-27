<?php

include("header.php");
include("../model/jarak.php");
include("../model/db.php");
include("../model/list_query.php");

$category = $_GET['kategori'];

$all = new jarak();
$result = $all->all(get_connection("../config.ini"),$category);
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>