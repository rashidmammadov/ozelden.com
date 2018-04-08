(function () {
    'use strict';

    function VocabularyService(DirectionService) {

        function getUserSession() {
            return DirectionService.serverUri() + 'usersession/getUserSession';
        }
        function getTutorInfo() {
            return DirectionService.serverUri() + 'tutor/getTutorInfo';
        }
        function tutorLogin() {
            return DirectionService.serverUri() + 'login/tutorLogin';
        }
        function tutorRegister() {
            return DirectionService.serverUri() + 'registration/tutorRegistration';
        }
        function updateTutorInfo() {
            return DirectionService.serverUri() + 'tutor/updateTutorInfo';
        }

        this.getUserSession = getUserSession;
        this.getTutorInfo = getTutorInfo;
        this.tutorLogin = tutorLogin;
        this.tutorRegister = tutorRegister;
        this.updateTutorInfo = updateTutorInfo;
        return this;
    }

    angular.module('ozelden.services').factory('VocabularyService', VocabularyService);
}());