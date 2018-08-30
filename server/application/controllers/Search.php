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
        	$params = array(
        		'offset' => $_GET['offset']
			);
            $result = $this->getTutorSearchResult($params);
        }
        echo json_encode($result);
    }

    private function getTutorSearchResult($params){
        $dbRequest = $this->SearchModel->tutorSearch($params);
		$lecturesData = $this->datalibrary->getLectures();

        $userList = array();
        foreach ($dbRequest as $user){
            $lecturesRequest = $this->TutorModel->getLecturesList($user[TUTOR_ID]);
            $lecturesList = $this->tutorlibrary->getLecturesList($lecturesRequest, $lecturesData);
            $suitableData = $this->TutorModel->getSuitabilitySchedule($user[TUTOR_ID]);
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
                'expression' => 9.1,
                'attention' => 7.3,
                'contact' => 8.5,
                LECTURES_LIST => $lecturesList,
				'regions' => json_decode($suitableData->region)
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
