(function () {
    'use strict';

    function VocabularyService(DirectionService) {
        var self = this;

        this.api = DirectionService.apiUri();

        this.data = function() { return self.api + 'data'; };
        this.suitabilitySchedule = function() { return self.api + 'suitabilitySchedule'; };
        this.refreshUser = function() { return  self.api + 'refreshUser'; };
        this.search = function() { return self.api + 'search/get'; };
        this.userClassList = function () { return self.api + 'userClassList'; };
        this.userLecturesList = function() { return self.api + 'userLecturesList'; };
        this.userLogin = function() { return self.api + 'login'; };
        this.userLogout = function() { return self.api + 'logout'; };
        this.userRegister = function() { return self.api + 'register'; };

        return this;
    }

    angular.module('ozelden.services').factory('VocabularyService', VocabularyService);
}());