<?php 

// akan mengimport file result_query.php untuk digunakan
require_once("result_query.php");

// akan mengimport file spk_saw.php untuk digunakan
require_once("spk_saw.php");


// ini adalah class pariwisata
class data_pariwisata {

    // variabel id
    public $id;

    // variabel kategori id
    public $kategori_id;

    // variabel nama
    public $nama;

    // variabel lokasi
    public $lokasi;

    // variabel jarak
    public $jarak;

    // variabel deskripsi
    public $deskripsi;

    // fungsi konstruksi class
    public function __construct(){
    }

    // fungsi untuk melakukan query data detail
    public function one($db) {
      
        // menyiapkan hasil query
        $result_query = new result_query();

        // membuat intance
        $one = new data_pariwisata();

        // perintah query
        $query = "SELECT id,kategori_id,nama,lokasi,jarak,deskripsi FROM data_pariwisata WHERE id=? LIMIT 1";
        
        // menyiapkan query
        $stmt = $db->prepare($query);

        // tempelkan parameter query
        $stmt->bind_param('i', $this->id);

        // eksekusi
        $stmt->execute();
       
        // jika terjadi error
        if ($stmt->error != ""){

            // tampilkan sebagai result error
            $result_query-> error = "error at query one data pariwisata : ".$stmt->error;
            
            // tutup koneksi
            $stmt->close();

            // balikan hasil query
            return $result_query;
        }

        // ambil hasil query 
        $result = $stmt->get_result()->fetch_assoc();

        // isi nilai id
        $one->id = $result['id'];

        // isi nilai kategori_id
        $one->kategori_id = $result['kategori_id'];

        // isi nilai nama
        $one->nama = $result['nama'];

        // isi nilai lokasi
        $one->lokasi = $result['lokasi'];

        // isi nilai jarak
        $one->jarak = $result['jarak'];

        // isi nilai deskripsi
        $one->deskripsi = $result['deskripsi'];

        // isi nilai data dengan data intance data pariwisata
        $result_query->data = $one;

        // tutup koneksi
        $stmt->close();

        // kembalikan hasil
        return $result_query;
    }

    // fungsi untuk melakukan query semua data
    public function all($db,$kategori_id,$fasilitas_id,$min_tiket_masuk,$min_jarak,$max_tiket_masuk,$max_jarak,$min_umur,$max_umur){
        
        // menyiapkan hasil query
        $result_query = new result_query();
        
        // menyiapkan array untuk menampung data
        $all = array();

        // jika umur bukan 0 maka isi dengan perintah query
        $queryUmur = $max_umur != 0 ? "AND (u.umur BETWEEN $min_umur AND $max_umur)" : "";

        // perintah query
        $query = "SELECT 
                    p.id AS id,p.nama AS nama,p.jarak AS jarak,
                    AVG(u.umur) AS umur,AVG(t.harga) AS harga,
                    (SELECT SUM(fp.fasilitas_id) FROM fasilitas_pariwisata fp WHERE fp.data_pariwisata_id = p.id) AS jumlah_fasilitas
                FROM 
                    data_pariwisata p
                INNER JOIN
                    tiket_masuk t
                ON
                    p.id = t.data_pariwisata_id
                INNER JOIN
                    fasilitas_pariwisata f
                ON
                    p.id = f.data_pariwisata_id    
                INNER JOIN
                    umur u
                ON
                    p.id = u.data_pariwisata_id
                WHERE
                    p.kategori_id = ?
                AND
                    f.fasilitas_id = ?
                AND
                    (p.jarak BETWEEN ? AND ?)
                AND
                    (t.harga BETWEEN ? AND ?)
                
                $queryUmur
                    
                GROUP BY 
                    p.id,p.nama,p.jarak";
        
        // re assign parameter request kategori_id
        $kategori = $kategori_id;

        // re assign parameter request fasilitas_id
        $fasilitas = $fasilitas_id;

        // re assign parameter request min_tiket_masuk
        $min_harga = $min_tiket_masuk;
        
        // re assign parameter request min_jarak
        $min_jrk = $min_jarak;

        // re assign parameter request max_tiket_masuk
        $max_harga = $max_tiket_masuk;

        // re assign parameter request max_jarak
        $max_jrk = $max_jarak;

        // menyiapkan query
        $stmt = $db->prepare($query);

        // tempelkan parameter query
        $stmt->bind_param('iiiiii',$kategori,$fasilitas,$min_jrk,$max_jrk,$min_harga,$max_harga);
        
        // eksekusi query
        $stmt->execute();

        // jika terjadi error
        if ($stmt->error != ""){

            // tampilkan sebagai result error
            $result_query-> error = "error at query all data_pariwisata : ".$stmt->error;

            // tutup koneksi
            $stmt->close();

            // kembalikan hasil
            return $result_query;
        }

        // check hasil dari query
        $rows = $stmt->get_result();

        // jika jumlah baris == 0
        if($rows->num_rows == 0){

            // tutup koneksi
            $stmt->close();

            // berikan array kosong
            $result_query->data = $all;

            // kembalikan hasil
            return $result_query;
        }

        // untuk setiap data baris yg berhasil diquery
        while ($result = $rows->fetch_assoc()){

            // buat instance
            $one = new kriteria();

            // isi nilai id
            $one->id = $result['id'];

            // isi nilai nama
            $one->nama = $result['nama'];

            // isi nilai jarak
            $one->jarak = $result['jarak'];

            // isi nilai umur
            $one->umur = $result['umur'];

            // isi nilai harga
            $one->tiket_masuk = $result['harga'];

            // isi nilai jumlah_fasilitas
            $one->jumlah_fasilitas = $result['jumlah_fasilitas'];

            // tambahkan kedalam data array
            array_push($all,$one);
        }

        // isi nilai data
        $result_query->data = $all;

        // tutup koneksi
        $stmt->close();

        // kembalikan hasil
        return $result_query;
    }

}


?>