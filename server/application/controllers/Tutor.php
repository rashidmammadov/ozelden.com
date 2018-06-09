<?php

class Tutor extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model(TUTOR_MODEL);
        $this->load->library(DATA_LIBRARY);
        $this->load->library(TUTOR_LIBRARY);
    }

    public function get(){
        $tutorId = $this->getTutorId();
        $result = array();
        if($tutorId){
            $actName = $_GET[ACT];
            if ($actName == LECTURES_LIST) {
                $result = $this->getLecturesList($tutorId);
            } else if ($actName == SUITABILITY_SCHEDULE) {
                $result = $this->getSuitabilitySchedule($tutorId);
            }
        }else{
            $result = array(
                FAILURE => "INVALID_TUTOR_ID"
            );
        }
        echo json_encode($result);
    }

    public function post(){
        $tutorId = $this->getTutorId();
        $result = array();
        if($tutorId){
            $request = file_get_contents("php://input");
            $post = json_decode($request, true);
            $actName = $post[ACT];
            $data = $post[DATA];

            if ($actName == ADD_LECTURE) {
                $result = $this->addLecture($tutorId, $data);
            } else if($actName == REMOVE_LECTURE) {
                $result = $this->removeLecture($tutorId, $data);
            } else if ($actName == SUITABILITY_SCHEDULE) {
                $result = $this->updateSuitabilitySchedule($tutorId, $data);
            }
        }else{
            $result = array(
                FAILURE => "INVALID_TUTOR_ID"
            );
        }
        echo json_encode($result);
    }

    private function addLecture($tutorId, $data){
        $dbRequest = $this->TutorModel->addLecture($tutorId, $data);

        if ($dbRequest){
            $result = array(
                SUCCESS => true,
                MESSAGE => 'LECTURE_ADDED_SUCCESSFULLY'
            );
        } else {
            $result = array(
                FAILURE => 'SOMETHING_WHEN_WRONG_WHILE_ADDING_LECTURE'
            );
        }
        return $result;
    }

    private function getLecturesList($tutorId){
        $dbRequest = $this->TutorModel->getLecturesList($tutorId);
        $lecturesData = $this->datalibrary->getLectures();
        $lecturesList = $this->tutorlibrary->getLecturesList($dbRequest, $lecturesData);

        $result = array(
            SUCCESS => true,
            DATA => $lecturesList
        );
        return $result;
    }

    private function getSuitabilitySchedule($tutorId){
        $data = $this->TutorModel->getSuitabilitySchedule($tutorId);
        $resultData = $this->tutorlibrary->prepareSuitabilitySchedule($data);

        $result = array(
            SUCCESS => true,
            DATA => $resultData
        );
        return $result;
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

    private function removeLecture($tutorId, $data){
        $dbRequest = $this->TutorModel->removeTutorLecture($tutorId, $data);

        if ($dbRequest) {
            $result = array(
                SUCCESS => true,
                MESSAGE => 'LECTURE_REMOVED_SUCCESSFULLY'
            );
        } else {
            $result = array(
                FAILURE => 'SOMETHING_WENT_WRONG_WHILE_REMOVING_LECTURE'
            );
        }
        return $result;
    }

    private function updateSuitabilitySchedule($tutorId, $data){
        $dbRequest = $this->TutorModel->updateSuitabilitySchedule($tutorId, $data);

        if ($dbRequest){
            $result = array(
                SUCCESS => true,
                MESSAGE => 'CHANGES_UPDATED_SUCCESSFULLY'
            );
        } else {
            $result = array(
                FAILURE => 'SOMETHING_WENT_WRONG_WHILE_SAVING_CHANGES'
            );
        }
        return $result;
    }
}
?>