(function () {
    'use strict';
    
    function UserSettingService($http, $q, VocabularyService, CookieService) {

        var self = this;

        var headers = {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + CookieService.getUser().remember_token
        };

        this.deferred;

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#addToUserClassList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description add given class to user`s class list.
         * @param {Object} classObject - holds the class.
         */
        function addToUserClassList(classObject) {
            self.deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.userClassList(),
                headers: headers,
                data: classObject
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#addToUserLectureList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description add given lecture to user`s lecture list.
         * @param {Object} lectureObject - holds the lecture.
         */
        function addToUserLectureList(lectureObject) {
            self.deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.userLecturesList(),
                headers: headers,
                data: lectureObject
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#getUserClassList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description get selected user`s class list.
         */
        function getUserClassList() {
            self.deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.userClassList(),
                headers: headers
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#getUserLectureList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description get selected user`s lecture list.
         * @param {Object} params - holds the user id and response type.
         */
        function getUserLectureList(params) {
            self.deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.userLecturesList(),
                headers: headers,
                params: params
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
                headers: headers,
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
                headers: headers,
                data: data
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#updateUserClass
         * @methodOf ozelden.services.UserSettingService
         *
         * @description update selected user`s given class.
         * @param {Object} data - holds the class data.
         */
        function updateUserClass(data) {
            self.deferred = $q.defer();

            $http({
                method: 'PUT',
                url: VocabularyService.userClassList(),
                headers: headers,
                data: data
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#removeLectureFromUserLectureList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description remove selected lecture from user`s lecture list.
         * @param {Object} data - holds the selected lecture`s data.
         */
        function removeLectureFromUserLectureList(data) {
            self.deferred = $q.defer();

            $http({
                method: 'DELETE',
                url: VocabularyService.userLecturesList(),
                headers: headers,
                data: data
            }).then($$fetchSuccessResponse, $$fetchFailureResponse);

            return self.deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#$$fetchSuccessResponse
         * @methodOf ozelden.services.UserSettingService
         * @description call when response is success.
         */
        function $$fetchSuccessResponse(response) {
            var result = response.data;
            if (result.status === "success") {
                self.deferred.resolve(result);
            } else {
                self.deferred.reject(result);
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#$$fetchFailureResponse
         * @methodOf ozelden.services.UserSettingService
         * @description call when response is failure.
         */
        function $$fetchFailureResponse(rejection) {
            self.deferred.reject(rejection);
        }

        this.addToUserClassList = addToUserClassList;
        this.addToUserLectureList = addToUserLectureList;
        this.getUserClassList = getUserClassList;
        this.getUserLectureList = getUserLectureList;
        this.getSuitabilitySchedule = getSuitabilitySchedule;
        this.updateSuitabilitySchedule = updateSuitabilitySchedule;
        this.updateUserClass = updateUserClass;
        this.removeLectureFromUserLectureList = removeLectureFromUserLectureList;
        return this;
    }

    angular.module('ozelden.services').factory('UserSettingService', UserSettingService);
})();