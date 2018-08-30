<?php

class SearchModel extends CI_Model{

    function __construct() {
        parent::__construct();
    }

    public function tutorSearch($params){
        $sql = "SELECT * FROM tutor AS T WHERE tutorState = '0' LIMIT 2 OFFSET ".$params['offset']."";
        $query = $this->db->query($sql);

        return $query->result_array();
    }
}
?>
