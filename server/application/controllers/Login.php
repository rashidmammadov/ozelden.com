<?php

class Login extends CI_Controller{

    public function tutorLogin(){
        $email = $_GET['email'];
        $password = $_GET['password'];
        $state = false;
        $message = "";

        $query = $this->db->query("SELECT * FROM tutor WHERE tutorEmail='".$email."' AND tutorPassword='".$password."'");
        $tutor = $query->row();

        if($tutor){
            $state = true;
            $this->setUserSession($tutor);
        }else{
            $state = false;
            $message = "WRONG_EMAIL_OR_PASSWORD";
        }

        if($state){
            $result = array(
                'status' => 'success',
                'userLoggedIn' => $this->session->userdata('userLoggedIn'),
                'user' => $this->session->userdata('user'),
            );
        }else{
            $result = array(
                'status' => 'failure',
                'message' => $message
            );
        }

        echo json_encode($result);
    }

    public function setUserSession($tutor){
        $param = array(
            'userType' => 'tutor',
            'id' => $tutor->tutorId,
            'name' => $tutor->tutorName,
            'surname' => $tutor->tutorSurname,
            'birthDate' => $tutor->tutorBirthDate,
            'email' => $tutor->tutorEmail,
            'sex' => $tutor->tutorSex,
            'telephone' => $tutor->tutorTelephone
        );
        $this->session->set_userdata('userLoggedIn', true);
        $this->session->set_userdata('user', $param);
    }
}
?>