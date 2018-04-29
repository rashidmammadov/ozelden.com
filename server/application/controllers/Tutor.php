<?php

class Tutor extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model(TUTOR_MODEL);
        $this->load->library(TUTOR_LIBRARY);
    }

    public function getTutorInfo(){
        $tutorId = $this->getTutorId();
        if($tutorId){
            $actName = $_GET[ACT];
            switch ($actName){
                case LECTURES_LIST:
                    $this->getLecturesList($tutorId);
                    break;
                case SUITABILITY_SCHEDULE:
                    $this->getSuitabilitySchedule($tutorId);
                    break;
            }
        }else{
            $result = array(
                'failure' => "INVALID_TUTOR_ID"
            );
            echo json_encode($result);
        }
    }

    public function updateTutorInfo(){
        $tutorId = $this->getTutorId();
        if($tutorId){
            $request = file_get_contents("php://input");
            $post = json_decode($request, true);
            $actName = $post[ACT];
            $data = $post[DATA];

            switch ($actName){
                case SUITABILITY_SCHEDULE:
                    $this->updateSuitabilitySchedule($tutorId, $data);
                    break;
                case ADD_LECTURE:
                    $this->addLecture($tutorId, $data);
                    break;
            }
        }else{
            $result = array(
                'failure' => "INVALID_TUTOR_ID"
            );
            echo json_encode($result);
        }
    }

    private function addLecture($tutorId, $data){
        $dbRequest = $this->TutorModel->addLecture($tutorId, $data);

        if ($dbRequest){
            $result = array(
                'success' => true,
                'message' => 'LECTURE_ADDED_SUCCESSFULLY'
            );
        } else {
            $result = array(
                'failure' => 'SOMETHING_WHEN_WRONG_WHILE_ADDING_LECTURE'
            );
        }
        echo json_encode($result);
    }

    private function getLecturesList($tutorId){
        $dbRequest = $this->TutorModel->getLecturesList($tutorId);

        $lecturesList = array();
        foreach($dbRequest as $row){
            $list = array(
                'lectureArea' => $row['lectureArea'],
                'lectureTheme' => $row['lectureTheme'],
                'experience' => $row['experience'],
                'price' => $row['price'],
                'currency' => $row['currency']
            );
            array_push($lecturesList, $list);
        }

        if($lecturesList){
            $result = array(
                'success' => true,
                'data' => $lecturesList
            );
        }
        echo json_encode($result);
    }

    private function getSuitabilitySchedule($tutorId){
        $data = $this->TutorModel->getSuitabilitySchedule($tutorId);
        $resultData = $this->tutorlibrary->prepareSuitabilitySchedule($data);

        $result = array(
            'success' => true,
            'data' => $resultData
        );
        echo json_encode($result);
    }

    private function getTutorId(){
        $user = $this->session->userdata(USER);
        if($user[USER_TYPE] == TUTOR){
            $tutorId = $user[IDENTIFIER];
        }else{
            $tutorId = false;
        }
        return $tutorId;
    }

    private function updateSuitabilitySchedule($tutorId, $data){
        $dbRequest = $this->TutorModel->updateSuitabilitySchedule($tutorId, $data);

        if ($dbRequest){
            $result = array(
                'success' => true,
                'message' => 'CHANGES_UPDATED_SUCCESSFULLY'
            );
        } else {
            $result = array(
                'failure' => 'SOMETHING_WENT_WRONG_WHILE_SAVING_CHANGES'
            );
        }
        echo json_encode($result);
    }
}
?>