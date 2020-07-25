<?php

// fungsi get connection
// yang membutuhkan parameter
// path ke file config.ini
function get_connection($config){

    // membuka file config
    // dari path yg disediakan
    $configs = parse_ini_file($config,true);

    // menggambil value username
    $username = $configs['database']['username'];

    // menggambil value password
    $password = $configs['database']['password'];

    // menggambil value host
    $host = $configs['database']['host'];

    // menggambil value port
    $port = $configs['database']['port'];

    // menggambil value database
    $dbname = $configs['database']['name'];
    
    // memanggil fungsi mysqli
    // mysqli adalah fungsi untuk 
    // koneksi aman ke database
    // yg saat ini digunakan
    $db = new mysqli($host,$username,$password,$dbname);

    // jika terjadi error
    if ($db->connect_error) {

        // maka program akan dimatikkan
        // dan ditampilkan error
        die("Connection failed: " . $conn->connect_error);
    }

    // mengembalikan object db sebagai hasil
    // dari fungsi koneksi ke database
    return $db;
}


?>