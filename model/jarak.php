<?php 

include("result_query.php");

class jarak {
    public $nilai;

    public function __construct(){
    }

    public function all($db,$category) {

        $result_query = new result_query();
        $all = array();

        $query = "SELECT 
                    jarak
                FROM 
                    data_pariwisata
                WHERE
                    kategori = ?
                GROUP BY 
                jarak";
    
        $stmt = $db->prepare($query);
        $stmt->bind_param('s',$category);
        $stmt->execute();

        if ($stmt->error != ""){
            $result_query-> error = "error at query all jarak : ".$stmt->error;
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

            $one = new jarak();
            $one->nilai = $result['jarak'];
            array_push($all,$one);
        }
        $result_query->data = $all;

        $stmt->close();

        return $result_query;
    }
}


?>