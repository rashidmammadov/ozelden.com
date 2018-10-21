(function () {
    'use strict';

    function VocabularyService(DirectionService) {
        var self = this;

        this.api = DirectionService.apiUri();

        function data() { return self.api + 'data'; }
        function myUser() { return  self.api + 'myUser' }
        function search() { return self.api + 'search/get'; }
        function updateTutorInfo() { return self.api + 'tutor/post'; }
        function userLogin() { return self.api + 'login'; }
        function userLogout() { return self.api + 'logout'; }
        function userRegister() { return self.api + 'register'; }

        this.data = data;
        this.myUser = myUser;
        this.search = search;
        this.updateTutorInfo = updateTutorInfo;
        this.userLogin = userLogin;
        this.userLogout = userLogout;
        this.userRegister = userRegister;
        return this;
    }

    angular.module('ozelden.services').factory('VocabularyService', VocabularyService);
}());