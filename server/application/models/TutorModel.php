<?php

class TutorModel extends CI_Model{

    function __construct() {
        parent::__construct();
    }

    public function addTutor($data){
        $this->db->insert('tutor', $data);
    }

    public function checkTutorByEmail($email){
        $query = $this->db->query("SELECT * FROM tutor WHERE tutorEmail = '".$email."'");
        return $query->num_rows();
    }

    public function createTutorSuitabilitySchedule($tutorId){
        $sql = "INSERT INTO tutor_suitability_schedule (tutorId)  VALUES (".$tutorId.")";
        $this->db->query($sql);
    }

    public function getSuitabilitySchedule($tutorId){
        $sql = "SELECT * FROM tutor_suitability_schedule AS TSS WHERE TSS.tutorId = '".$tutorId."'";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getTutorByEmail($email){
        $query = $this->db->query("SELECT * FROM tutor WHERE tutorEmail = '".$email."'");
        return $query->result();
    }

    public function updateSuitabilitySchedule($tutorId, $data){
        $region = json_encode($data['region'], JSON_UNESCAPED_UNICODE);
        $location = json_encode($data['location'], JSON_UNESCAPED_UNICODE);
        $courseType = json_encode($data['courseType'], JSON_UNESCAPED_UNICODE);
        $facility = json_encode($data['facility'], JSON_UNESCAPED_UNICODE);
        $dayHourTable = json_encode($data['dayHourTable'], JSON_UNESCAPED_UNICODE);

        $sql = "UPDATE tutor_suitability_schedule SET region = '".$region."', location = '".$location."', courseType = '".$courseType."', 
            facility = '".$facility."', dayHourTable = '".$dayHourTable."' WHERE tutorId = ".$tutorId." ";

        $result = $this->db->query($sql);
        return $result ? true : false;
    }
}
?>