<?php 

// akan mengimport file math.php untuk digunakan
include("../util/math.php");

// untuk semua fungsi saw
// refresnsi pembelajaran
// saya ambil dari sini
// https://medium.com/skyshidigital/sistem-pengambilan-keputusan-dengan-algoritma-saw-simple-additive-weighting-524a43ef316

// ini adalah class kriteria
class kriteria {

    // variabel jarak (cost)
    public $jarak;

    // variabel umur (cost)
    public $umur;

    // variabel tiket_masuk (cost)
    public $tiket_masuk;

    // variabel jumlah_fasilitas (benefit)
    public $jumlah_fasilitas;

    // variabel pakai_umur
    // yg akan menentukan apakah bobot
    // umur akan digunakan
    public $pakai_umur;

    // fungsi konstruksi class
    public function __construct(){
    }


    // fungsi untuk mendapatkan nilai terbesar
    // atau terkecil dari setiap data yg 
    // berhasil di query
    public function getMinMax($values) {

        // membuat intance untuk menampung nilai2
        $result = new kriteria();

        // ambil nilai terkecil dari jarak dari setiap data
        $result->jarak = (float) min_attribute_in_array($values, "jarak");

        // ambil nilai terkecil daritiket_masuk dari setiap data
        $result->tiket_masuk = (float) min_attribute_in_array($values, "tiket_masuk"); 
        
        // ambil nilai terkecil dari jumlah_fasilitas dari setiap data
        $result->jumlah_fasilitas = (float) max_attribute_in_array($values, "jumlah_fasilitas");
        
        // jika umur ingin digunakan
        if ($this->pakai_umur){

            // ambil nilai terkecil dari umur dari setiap data
            $result->umur = (int) min_attribute_in_array($values, "umur");
        }

        // kembalikan hasil
        return $result;
    }

    // fungsi untuk melakukan normalisasi
    // dari setiap data yg berhasil di query
    public function normalisasi($values, $maxmin) {

        // untuk setiap data
        foreach ($values as $value) {

            // normalisasikan jarak
            $value->jarak = (float)$maxmin->jarak / (float)$value->jarak;

            // normalisasikan tiket_masuk
            $value->tiket_masuk =  (float)$maxmin->tiket_masuk / (float)$value->tiket_masuk;
            
            // normalisasikan jumlah_fasilitas
            $value->jumlah_fasilitas = (float)$value->jumlah_fasilitas /  (float)$maxmin->jumlah_fasilitas;
            
            // jika umur ingin digunakan
            if ($this->pakai_umur){

                // normalisasikan umur
                $value->umur = (float)$maxmin->umur / (float)$value->umur;
            }            
        }
    
        // kembalikan hasil
        return $values;
    }

    // fungsi untuk menghitung nilai akhir
    // dari setiap data yg berhasil di query
    public function hitungPeringkat($values) {

        // buat array untuk menampung data
        $results = array();

        // untuk setiap data
        foreach ($values as $value) {

            // buat instance
            $one = new result_spk();

            // isi nilai id
            $one->id = $value->id;

            // isi nilai nama
            $one->nama = $value->nama;

            // hitung nilai umur
            $total_umur = ($this->pakai_umur ? ((float)$value->umur * (float)$this->umur) : 0);
            
            // hitung nilai tiket_masuk
            $total_tiket_masuk = ((float)$value->tiket_masuk * (float)$this->tiket_masuk);
            
            // hitung nilai jarak
            $total_jarak = ((float)$value->jarak * (float)$this->jarak);
            
            // hitung nilaifasilitas 
            $total_fasilitas = ((float)$value->jumlah_fasilitas * (float)$this->jumlah_fasilitas);
            
            // jumlahkan nilai total semua
            $one->total =  $total_tiket_masuk + $total_umur + $total_jarak + $total_fasilitas;
            
            // tambahkan ke dalam array
            array_push($results,$one);
        }

        // kembalikan hasil
        return $results;
    }
}

// ini adalah result spk
// yang nantinya akan menampilkan nama
// dan nilai hasil saw
class result_spk {

    // variabel id
    public $id;

    // variabel nama
    public $nama;

    // variabel total
    public $total;

    // fungsi konstruksi class
    public function __construct(){
    }
}

?>