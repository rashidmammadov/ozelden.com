(function () {
    'use strict';
    
    function UserSettingService($http, $q, VocabularyService, CookieService) {

        var self = this;

        this.deferred;

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#addToUserLectureList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description add given leture object to user2s lecture list.
         * @param {Object} lectureObject - holds the lecture.
         */
        function addToUserLectureList(lectureObject) {
            self.deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.lecturesList(),
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + CookieService.getUser().remember_token
                },
                data: lectureObject
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#getSuitabilitySchedule
         * @methodOf ozelden.services.UserSettingService
         *
         * @description get selected user`s suitability schedule.
         * @param {Integer} id - holds the user`s id.
         */
        function getSuitabilitySchedule(id) {
            self.deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.suitabilitySchedule(),
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + CookieService.getUser().remember_token
                },
                params: { id: id }
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#updateSuitabilitySchedule
         * @methodOf ozelden.services.UserSettingService
         *
         * @description update selected user`s suitability schedule.
         * @param {Object} data - holds the user`s suitability settings.
         */
        function updateSuitabilitySchedule(data) {
            self.deferred = $q.defer();

            $http({
                method: 'PUT',
                url: VocabularyService.suitabilitySchedule(),
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + CookieService.getUser().remember_token
                },
                data: data
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        function $$fetchSuccessResponse(response) {
            var result = response.data;
            if (result.status === "success") {
                self.deferred.resolve(result);
            } else {
                self.deferred.reject(result);
            }
        }

        function $$fetchFailureResponse(rejection) {
            self.deferred.reject(rejection);
        }

        this.addToUserLectureList = addToUserLectureList;
        this.getSuitabilitySchedule = getSuitabilitySchedule;
        this.updateSuitabilitySchedule = updateSuitabilitySchedule;
        return this;
    }

    angular.module('ozelden.services').factory('UserSettingService', UserSettingService);
})();