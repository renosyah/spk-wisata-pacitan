<?php 

    // ini adalah fungsi yang mengizinkan akses dari domain manapun
    header('Access-Control-Allow-Origin: *');

    // ini adalah fungsi yang mengizinkan request dalam bentuk json
    header("Content-Type: application/json; charset=UTF-8");
    
?>