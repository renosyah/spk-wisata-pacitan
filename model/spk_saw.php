<?php 

include("../util/math.php");

// untuk semua fungsi saw
// refresnsi pembelajaran
// saya ambil dari sini
// https://medium.com/skyshidigital/sistem-pengambilan-keputusan-dengan-algoritma-saw-simple-additive-weighting-524a43ef316

class kriteria {
    public $jarak; // cost
    public $umur; // cost
    public $tiket_masuk; // cost

    public function __construct(){
    }


    public function getMinMax($values) {
        $result = new kriteria();

        $result->jarak = (float) min_attribute_in_array($values, "jarak");
        $result->umur = (float) min_attribute_in_array($values, "umur");
        $result->tiket_masuk = (float) min_attribute_in_array($values, "tiket_masuk");     

        return $result;
    }
    
    public function normalisasi($values, $maxmin) {
        foreach ($values as $value) {
            $value->jarak = (float)$maxmin->jarak / (float)$value->jarak;
            $value->umur = (float)$maxmin->umur / (float)$value->umur;
            $value->tiket_masuk =  (float)$maxmin->tiket_masuk / (float)$value->tiket_masuk;
        }
    
        return $values;
    }
    
    public function hitungPeringkat($values) {
        $results = array();
        foreach ($values as $value) {
            $one = new result_spk();
            $one->id = $value->id;
            $one->nama = $value->nama;
            $one->total = ((float)$value->jarak * (float)$this->jarak) + ((float)$value->umur * (float)$this->umur) + ((float)$value->tiket_masuk * (float)$this->tiket_masuk);
            array_push($results,$one);
        }
        return $results;
    }
}

class result_spk {
    public $id;
    public $nama;
    public $total;

    public function __construct(){
    }
}

?>