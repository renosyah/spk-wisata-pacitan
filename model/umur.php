<?php 

include("result_query.php");

class umur {
    public $nilai;

    public function __construct(){
    }

    public function all($db) {

        $result_query = new result_query();
        $all = array();

        $query = "SELECT 
                    umur
                FROM 
                    data_pariwisata
                GROUP BY 
                    umur";
    
        $stmt = $db->prepare($query);
        $stmt->execute();

        if ($stmt->error != ""){
            $result_query-> error = "error at query all umur : ".$stmt->error;
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

            $one = new umur();
            $one->nilai = $result['umur'];
            array_push($all,$one);
        }
        $result_query->data = $all;

        $stmt->close();

        return $result_query;
    }
}


?>