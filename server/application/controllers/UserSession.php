<?php

class UserSession extends CI_Controller{

    public function getUserSession(){
        if($this->session->userdata('userLoggedIn')) {
            $result = array(
                'status' => 'success',
                'user' => $this->session->userdata('user')
            );
        } else {
            $result = array(
                'status' => 'failure'
            );
        }

        echo json_encode($result);
    }
}

?>