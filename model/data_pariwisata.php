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

    public function add($db) {

        $result_query = new result_query();
        $result_query->data = "ok";

        $query = "INSERT INTO data_pariwisata (kategori_id,nama,lokasi,jarak,deskripsi) VALUES (?,?,?,?,?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param('issis', $this->kategori_id,$this->nama,$this->lokasi,$this->jarak,$this->deskripsi);
        $stmt->execute();

        if ($stmt->error != ""){
            $result_query->error =  "error at add new pariwisata : ".$stmt->error;
            $result_query->data = "not ok";
        }
        $stmt->close();

        return $result_query;
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

    public function all($db,$list_query) {

        $result_query = new result_query();
        $all = array();

        $query = "SELECT 
                    id,kategori_id,nama,lokasi,jarak,deskripsi 
                FROM 
                    data_pariwisata
                WHERE
                    ".$list_query->search_by." LIKE ?
                ORDER BY
                    ".$list_query->order_by." ".$list_query->order_dir." 
                LIMIT ? 
                OFFSET ?";
    
        $stmt = $db->prepare($query);
        $search = "%".$list_query->search_value."%";
        $offset = $list_query->offset;
        $limit =  $list_query->limit;

        $stmt->bind_param('sii',$search ,$limit, $offset);
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

            $one = new data_pariwisata();
            $one->id = $result['id'];
            $one->kategori_id = $result['kategori_id'];
            $one->nama = $result['nama'];
            $one->lokasi = $result['lokasi'];
            $one->jarak = $result['jarak'];
            $one->deskripsi = $result['deskripsi'];
            array_push($all,$one);
        }
        $result_query->data = $all;

        $stmt->close();

        return $result_query;
    }

    public function allToCriteria($db,$kategori_id,$fasilitas_id,$tiket_masuk,$jarak,$umur){
        $result_query = new result_query();
        $all = array();

        $queryUmur = $umur != 0 ? "AND u.umur <= $umur" : "";

        $query = "SELECT 
                    p.id AS id,p.nama AS nama,p.jarak AS jarak,
                    AVG(u.umur) AS umur,AVG(t.harga) AS harga
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
                    p.jarak <= ?
                AND
                    t.harga <= ?
                    $queryUmur
                GROUP BY 
                    p.id,p.nama,p.jarak";
        

        $kategori = $kategori_id;
        $fasilitas = $fasilitas_id;
        $harga = $tiket_masuk;
        $jrk = $jarak;

        $stmt = $db->prepare($query);
        $stmt->bind_param('iiii',$kategori,$fasilitas,$jrk,$harga);
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
            array_push($all,$one);
        }
        $result_query->data = $all;

        $stmt->close();

        return $result_query;
    }

}


?>