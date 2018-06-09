<?php

class SearchModel extends CI_Model{

    function __construct() {
        parent::__construct();
    }

    public function tutorSearch(){
        $sql = "SELECT * FROM tutor AS T WHERE tutorState = '0'";
        $query = $this->db->query($sql);

        return $query->result_array();
    }
}
?>