<?php

class Registration extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('TutorModel');
    }

    /**
     * @ngdoc method {POST}
     * @name ozelden.controllers.controllers:tutorLoginCtrl#tutorRegistration
     * @description Check if user not exist with same email and create tutor.
     */
    public function tutorRegistration()
    {
        $request = file_get_contents("php://input");
        $post = json_decode($request, true);
        $state = false;
        $message = "";

        if ($post != null) {
            $count = $this->TutorModel->checkTutorByEmail($post['email']);
            if ($count == 0) {
                $data = array(
                    'tutorName' => $post['name'],
                    'tutorSurname' => $post['surname'],
                    'tutorBirthDate' => $post['birthDate'],
                    'tutorEmail' => $post['email'],
                    'tutorPassword' => $post['password'],
                    'tutorSex' => $post['sex'],
                    'tutorTelephone' => $post['telephone']
                );
                $this->TutorModel->addTutor($data);
                $this->setUserSession($data);
                $state = true;
            } else {
                $state = false;
                $message = "THIS_EMAIL_ALREADY_REGISTERED";
            }

            if ($state) {
                $result = array(
                    'status' => 'success',
                    'userLoggedIn' => $this->session->userdata('userLoggedIn'),
                    'user' => $this->session->userdata('user')
                );
            } else {
                $result = array(
                    'status' => 'failure',
                    'message' => $message
                );
            }
            echo json_encode($result);
        }else{
            echo "EXCEPTION";
        }
    }

    /**
     * @ngdoc method
     * @name ozelden.controllers.controllers:tutorLoginCtrl#setUserSession
     * @description Set session for registered tutor.
     *              Create suitability schedule with default values.
     * @param {Object} $data holds registered tutor info.
     */
    public function setUserSession($data){
        if($data) {
            $result = $this->TutorModel->getTutorByEmail($data['tutorEmail']);
            $param = array(
                'userType' => 'tutor',
                'id' => $result[0]->tutorId,
                'name' => $data['tutorName'],
                'surname' => $data['tutorSurname'],
                'birthDate' => $data['tutorBirthDate'],
                'email' => $data['tutorEmail'],
                'sex' => $data['tutorSex'],
                'telephone' => $data['tutorTelephone']
            );
            $this->session->set_userdata('userLoggedIn', true);
            $this->session->set_userdata('user', $param);

            $this->TutorModel->createTutorSuitabilitySchedule($result[0]->tutorId);
        }else{
            echo "EXCEPTION";
        }
    }
}
?>