<?php 

include("../util/math.php");

// untuk semua fungsi saw
// refresnsi pembelajaran
// saya ambil dari sini
// https://medium.com/skyshidigital/sistem-pengambilan-keputusan-dengan-algoritma-saw-simple-additive-weighting-524a43ef316

class criteria {
    public $jarak; // cost
    public $umur; // cost
    public $tiket_masuk; // cost

    public function __construct(){
    }


    public function getMinMax($values) {
        $result = new criteria();

        $result->jarak = min_attribute_in_array($values, "jarak");
        $result->umur = min_attribute_in_array($values, "umur");
        $result->tiket_masuk = min_attribute_in_array($values, "tiket_masuk");     

        return $result;
    }
    
    public function normalisasi($values, $maxmin) {
        foreach ($values as $value) {
            $value->jarak = $maxmin->jarak / $value->jarak;
            $value->umur = $maxmin->umur / $value->umur;
            $value->tiket_masuk =  $maxmin->tiket_masuk / $value->tiket_masuk;
        }
    
        return $values;
    }
    
    public function hitungPeringkat($values) {
        $results = array();
        foreach ($values as $value) {
            $one = new result_spk();
            $one->nama = $value->nama;
            $one->total = ($value->jarak * $this->jarak) + ($value->umur * $this->umur) + ($value->tiket_masuk * $this->tiket_masuk);
            array_push($results,$one);
        }
        return $results;
    }
}

class result_spk {
    public $nama;
    public $total;

    public function __construct(){
    }
}

?>