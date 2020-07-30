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
    public $jumlah_fasilitas; // benefit
    public $pakai_umur;

    public function __construct(){
    }


    public function getMinMax($values) {
        $result = new kriteria();

        $result->jarak = (float) min_attribute_in_array($values, "jarak");
        $result->tiket_masuk = (float) min_attribute_in_array($values, "tiket_masuk");     
        $result->jumlah_fasilitas = (float) max_attribute_in_array($values, "jumlah_fasilitas");
        if ($this->pakai_umur){
            $result->umur = (int) min_attribute_in_array($values, "umur");
        }

        return $result;
    }
    
    public function normalisasi($values, $maxmin) {
        foreach ($values as $value) {
            $value->jarak = (float)$maxmin->jarak / (float)$value->jarak;
            $value->tiket_masuk =  (float)$maxmin->tiket_masuk / (float)$value->tiket_masuk;
            $value->jumlah_fasilitas = (float)$value->jumlah_fasilitas /  (float)$maxmin->jumlah_fasilitas;
            if ($this->pakai_umur){
                $value->umur = (float)$maxmin->umur / (float)$value->umur;
            }            
        }
    
        return $values;
    }
    
    public function hitungPeringkat($values) {
        $results = array();
        foreach ($values as $value) {
            $one = new result_spk();
            $one->id = $value->id;
            $one->nama = $value->nama;

            $total_umur = ($this->pakai_umur ? ((float)$value->umur * (float)$this->umur) : 0);
            $total_tiket_masuk = ((float)$value->tiket_masuk * (float)$this->tiket_masuk);
            $total_jarak = ((float)$value->jarak * (float)$this->jarak);
            $total_fasilitas = ((float)$value->jumlah_fasilitas * (float)$this->jumlah_fasilitas);
            
            $one->total =  $total_tiket_masuk + $total_umur + $total_jarak + $total_fasilitas;
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