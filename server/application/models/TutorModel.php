<?php

class TutorModel extends CI_Model{

    function __construct() {
        parent::__construct();
    }

    public function addLecture($tutorId, $data){
        $lecture = array(
            TUTOR_ID => $tutorId,
            LECTURE_AREA => $data[LECTURE_AREA],
            LECTURE_THEME => $data[LECTURE_THEME],
            EXPERIENCE => $data[EXPERIENCE],
            PRICE => $data[PRICE]
        );

        $result = $this->db->insert('tutor_lectures_list', $lecture);
        return $result ? true : false;
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

    public function getLecturesList($tutorId){
        $sql = "SELECT * FROM tutor_lectures_list AS TLL WHERE TLL.tutorId = '".$tutorId."'";
        $query = $this->db->query($sql);

        return $query->result_array();
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

    public function removeTutorLecture($tutorId, $data){
        $query = $this->db->simple_query("DELETE FROM tutor_lectures_list WHERE tutorId = '".$tutorId."' AND 
            lectureArea = '".$data[LECTURE_AREA]."' AND lectureTheme = '".$data[LECTURE_THEME]."'");

        if(!$query){
            $this->exception_handler($this->db->error());
        }
        return $query;
    }

    public function updateSuitabilitySchedule($tutorId, $data){
        $region = json_encode($data[REGION], JSON_UNESCAPED_UNICODE);
        $location = json_encode($data[LOCATION], JSON_UNESCAPED_UNICODE);
        $courseType = json_encode($data[COURSE_TYPE], JSON_UNESCAPED_UNICODE);
        $facility = json_encode($data[FACILITY], JSON_UNESCAPED_UNICODE);
        $dayHourTable = json_encode($data[DAY_HOUR_TABLE], JSON_UNESCAPED_UNICODE);

        $sql = "UPDATE tutor_suitability_schedule SET region = '".$region."', location = '".$location."', courseType = '".$courseType."', 
            facility = '".$facility."', dayHourTable = '".$dayHourTable."' WHERE tutorId = ".$tutorId." ";

        $query = $this->db->simple_query($sql);
        if(!$query){
            $this->exception_handler($this->db->error());
        }
        return $query;
    }

    private function exception_handler($error){
        //TODO
    }
}
?>