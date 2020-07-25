<?php

// class list query
// ini adalah class yg nantinya akan dijadikan
// object untuk query data-data yg banyak
// ke database
class list_query {

    // variabel search by
    public $search_by;
    
    // variabel search value
    public $search_value;

    // variabel order by
    public $order_by;

    // variabel order dir
    public $order_dir;

    // variabel offset
    public $offset;

    // variabel limit
    public $limit;

    // konstruksi
    // fungsi yg akan dipanggil saat
    // membuat object
    public function __construct(){

    }
}

?>