<?php 

require_once("result_query.php");
require_once("spk_saw.php");

class data_pariwisata {
    public $id;
    public $kategori_id;
    public $nama;
    public $lokasi;
    public $jarak;
    public $deskripsi;

    public function __construct(){
    }

    public function one($db) {
      
        $result_query = new result_query();
        $one = new data_pariwisata();

        $query = "SELECT id,kategori_id,nama,lokasi,jarak,deskripsi FROM data_pariwisata WHERE id=? LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
       
        if ($stmt->error != ""){
            $result_query-> error = "error at query one data pariwisata : ".$stmt->error;
            $stmt->close();
            return $result_query;
        }

        $result = $stmt->get_result()->fetch_assoc();

        $one->id = $result['id'];
        $one->kategori_id = $result['kategori_id'];
        $one->nama = $result['nama'];
        $one->lokasi = $result['lokasi'];
        $one->jarak = $result['jarak'];
        $one->deskripsi = $result['deskripsi'];
        $result_query->data = $one;

        $stmt->close();

        return $result_query;
    }

    public function all($db,$kategori_id,$fasilitas_id,$min_tiket_masuk,$min_jarak,$max_tiket_masuk,$max_jarak,$min_umur,$max_umur){
        $result_query = new result_query();
        $all = array();

        $queryUmur = $max_umur != 0 ? "AND (u.umur BETWEEN $min_umur AND $max_umur)" : "";

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
        

        $kategori = $kategori_id;
        $fasilitas = $fasilitas_id;
        $min_harga = $min_tiket_masuk;
        $min_jrk = $min_jarak;
        $max_harga = $max_tiket_masuk;
        $max_jrk = $max_jarak;

        $stmt = $db->prepare($query);
        $stmt->bind_param('iiiiii',$kategori,$fasilitas,$min_jrk,$max_jrk,$min_harga,$max_harga);
        $stmt->execute();

        if ($stmt->error != ""){
            $result_query-> error = "error at query all data_pariwisata : ".$stmt->error;
            $stmt->close();

            return $result_query;
        }


        $rows = $stmt->get_result();

        if($rows->num_rows == 0){
            $stmt->close();
            $result_query->data = $all;

            return $result_query;
        }

        while ($result = $rows->fetch_assoc()){

            $one = new kriteria();
            $one->id = $result['id'];
            $one->nama = $result['nama'];
            $one->jarak = $result['jarak'];
            $one->umur = $result['umur'];
            $one->tiket_masuk = $result['harga'];
            $one->jumlah_fasilitas = $result['jumlah_fasilitas'];
            array_push($all,$one);
        }
        $result_query->data = $all;

        $stmt->close();

        return $result_query;
    }

}


?>