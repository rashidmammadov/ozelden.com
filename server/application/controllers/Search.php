<?php

class Search extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model(SEARCH_MODEL);
        $this->load->model(TUTOR_MODEL);
        $this->load->library(DATA_LIBRARY);
        $this->load->library(TUTOR_LIBRARY);
    }

    public function get(){
        $actName = $_GET[ACT];
        $result = array();
        if($actName == 'tutorSearch'){
            $result = $this->getTutorSearchResult();
        }
        echo json_encode($result);
    }

    private function getTutorSearchResult(){
        $dbRequest = $this->SearchModel->tutorSearch();

        $userList = array();
        foreach ($dbRequest as $user){
            $lecturesRequest = $this->TutorModel->getLecturesList($user[TUTOR_ID]);
            $lecturesData = $this->datalibrary->getLectures();
            $lecturesList = $this->tutorlibrary->getLecturesList($lecturesRequest, $lecturesData);
            $userData = array(
                'id' => $user[TUTOR_ID],
                'name' => $user['tutorName'],
                'surname' => $user['tutorSurname'],
                'birthDate' => (float)$user['tutorBirthDate'],
                'email' => $user['tutorEmail'],
                'sex' => $user['tutorSex'],
                'telephone' => $user['tutorTelephone'],
                'image' => $user['tutorImage'],
                'registerDate' => strtotime($user['tutorRegisterDate'])*1000,
                'average' => 9,
                'knowledge' => 8.5,
                'expression' => 9.1,
                'attention' => 7.3,
                LECTURES_LIST => $lecturesList
            );

            array_push($userList, $userData);
        }
        $result = array(
            SUCCESS => true,
            DATA => $userList
        );
        return $result;
    }
}
?>