<?php 

include("result_query.php");

class kategori {
    public $id;
    public $nama;

    public function __construct(){
    }

    public function add($db) {

        $result_query = new result_query();
        $result_query->data = "ok";

        $query = "INSERT INTO kategori (nama) VALUES (?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $this->nama);
        $stmt->execute();

        if ($stmt->error != ""){
            $result_query->error =  "error at add new kategori : ".$stmt->error;
            $result_query->data = "not ok";
        }
        $stmt->close();

        return $result_query;
    }

    public function all($db,$list_query) {

        $result_query = new result_query();
        $all = array();

        $query = "SELECT 
                    id,nama 
                FROM 
                    kategori
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
            $result_query-> error = "error at query all kategori : ".$stmt->error;
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

            $one = new kategori();
            $one->id = $result['id'];
            $one->nama = $result['nama'];
            array_push($all,$one);
        }
        $result_query->data = $all;

        $stmt->close();

        return $result_query;
    }    
}


?>