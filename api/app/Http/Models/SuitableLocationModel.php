<?php


namespace App\Http\Models;


class SuitableLocationModel {

    private $studentHome;
    private $tutorHome;
    private $etude;
    private $course;
    private $library;
    private $overInternet;

    public function __construct($parameters = null) {
        $this->setStudentHome($parameters[STUDENT_HOME]);
        $this->setTutorHome($parameters[TUTOR_HOME]);
        $this->setEtude($parameters[ETUDE]);
        $this->setCourse($parameters[COURSE]);
        $this->setLibrary($parameters[LIBRARY]);
        $this->setOverInternet($parameters[OVER_INTERNET]);
    }

    public function get() {
        return array(
            STUDENT_HOME => $this->getStudentHome(),
            TUTOR_HOME => $this->getTutorHome(),
            ETUDE => $this->getEtude(),
            COURSE => $this->getCourse(),
            LIBRARY => $this->getLibrary(),
            OVER_INTERNET => $this->getOverInternet()
        );
    }

    public function getStudentHome() {
        return !!$this->studentHome;
    }

    public function setStudentHome($studentHome): void {
        $this->studentHome = $studentHome;
    }

    public function getTutorHome() {
        return !!$this->tutorHome;
    }

    public function setTutorHome($tutorHome): void {
        $this->tutorHome = $tutorHome;
    }

    public function getEtude() {
        return !!$this->etude;
    }

    public function setEtude($etude): void {
        $this->etude = $etude;
    }

    public function getCourse() {
        return !!$this->course;
    }

    public function setCourse($course): void {
        $this->course = $course;
    }

    public function getLibrary() {
        return !!$this->library;
    }

    public function setLibrary($library): void {
        $this->library = $library;
    }

    public function getOverInternet() {
        return !!$this->overInternet;
    }

    public function setOverInternet($overInternet): void {
        $this->overInternet = $overInternet;
    }

}
