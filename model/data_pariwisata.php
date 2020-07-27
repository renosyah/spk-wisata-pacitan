<?php 

include("result_query.php");

class data_pariwisata {
    public $id;
    public $nama;
    public $lokasi;
    public $kategori;
    public $jarak;
    public $tiket_masuk;
    public $fasilitas;
    public $umur;
    public $deskripsi;

    public function __construct(){
    }

    public function add($db) {

        $result_query = new result_query();
        $result_query->data = "ok";

        $query = "INSERT INTO data_pariwisata (nama,lokasi,kategori,jarak,tiket_masuk,fasilitas,umur) VALUES (?,?,?,?,?,?,?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param('sssiisi', $this->nama,$this->lokasi,$this->kategori,$this->jarak,$this->tiket_masuk,$this->fasilitas,$this->umur);
        $stmt->execute();

        if ($stmt->error != ""){
            $result_query->error =  "error at add new pariwisata : ".$stmt->error;
            $result_query->data = "not ok";
        }
        $stmt->close();

        return $result_query;
    }

    public function oneByNama($db) {
      
        $result_query = new result_query();
        $one = new data_pariwisata();

        $query = "SELECT id,nama,lokasi,kategori,jarak,tiket_masuk,fasilitas,umur,deskripsi FROM data_pariwisata WHERE nama=? LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $this->nama);
        $stmt->execute();
       
        if ($stmt->error != ""){
            $result_query-> error = "error at query one data pariwisata : ".$stmt->error;
            $stmt->close();
            return $result_query;
        }

        $result = $stmt->get_result()->fetch_assoc();

        $one->id = $result['id'];
        $one->nama = $result['nama'];
        $one->lokasi = $result['lokasi'];
        $one->kategori = $result['kategori'];
        $one->jarak = $result['jarak'];
        $one->tiket_masuk = $result['tiket_masuk'];
        $one->fasilitas = $result['fasilitas'];
        $one->umur = $result['umur'];
        $one->deskripsi = $result['deskripsi'];
        $result_query->data = $one;

        $stmt->close();

        return $result_query;
    }

    public function one($db) {
      
        $result_query = new result_query();
        $one = new data_pariwisata();

        $query = "SELECT id,nama,lokasi,kategori,jarak,tiket_masuk,fasilitas,umur,deskripsi FROM data_pariwisata WHERE id=? LIMIT 1";
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
        $one->nama = $result['nama'];
        $one->lokasi = $result['lokasi'];
        $one->kategori = $result['kategori'];
        $one->jarak = $result['jarak'];
        $one->tiket_masuk = $result['tiket_masuk'];
        $one->fasilitas = $result['fasilitas'];
        $one->umur = $result['umur'];
        $one->deskripsi = $result['deskripsi'];
        $result_query->data = $one;

        $stmt->close();

        return $result_query;
    }

    public function all($db,$list_query) {

        $result_query = new result_query();
        $all = array();

        $query = "SELECT 
                    id,nama,lokasi,kategori,jarak,tiket_masuk,fasilitas,umur,deskripsi 
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
            $one->nama = $result['nama'];
            $one->lokasi = $result['lokasi'];
            $one->kategori = $result['kategori'];
            $one->jarak = $result['jarak'];
            $one->tiket_masuk = $result['tiket_masuk'];
            $one->fasilitas = $result['fasilitas'];
            $one->umur = $result['umur'];
            $one->deskripsi = $result['deskripsi'];
            array_push($all,$one);
        }
        $result_query->data = $all;

        $stmt->close();

        return $result_query;
    }


    public function allGrouping($db,$category,$facility,$price,$distance,$age) {

        $result_query = new result_query();
        $all = array();

        $query = "SELECT 
                    nama,tiket_masuk,jarak,umur 
                FROM 
                    data_pariwisata 
                WHERE 
                    kategori = ?
                AND
                    fasilitas = ?
                AND 
                    tiket_masuk <= ?
                AND
                    jarak <= ?
                AND
                    umur <= ?
                GROUP BY 
                    nama,jarak,tiket_masuk,umur";
    
        $stmt = $db->prepare($query);
        $stmt->bind_param('ssiii',$category,$facility,$price,$distance,$age);
        $stmt->execute();

        if ($stmt->error != ""){
            $result_query-> error = "error at query all grouping data_pariwisata : ".$stmt->error;
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
            $one->nama = $result['nama'];
            $one->jarak = $result['jarak'];
            $one->tiket_masuk = $result['tiket_masuk'];
            $one->umur = $result['umur'];

            $one->id = 0;
            $one->lokasi = "";
            $one->kategori = "";
            $one->fasilitas = "";
            array_push($all,$one);
        }
        $result_query->data = $all;

        $stmt->close();

        return $result_query;
    }

}


?>