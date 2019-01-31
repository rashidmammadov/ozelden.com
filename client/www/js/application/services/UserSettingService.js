(function () {
    'use strict';
    
    function UserSettingService($http, $q, VocabularyService, CookieService) {

        var self = this;

        var headers = {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + CookieService.getUser().remember_token
        };

        /**
         * @ngdoc {POST} method
         * @name ozelden.services.UserSettingService#addToUserClassList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description add given class to user`s class list.
         * @param {Object} classObject - holds the class.
         */
        function addToUserClassList(classObject) {
            var deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.userClassList(),
                headers: headers,
                data: classObject
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {POST} method
         * @name ozelden.services.UserSettingService#addToUserLectureList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description add given lecture to user`s lecture list.
         * @param {Object} lectureObject - holds the lecture.
         */
        function addToUserLectureList(lectureObject) {
            var deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.userLecturesList(),
                headers: headers,
                data: lectureObject
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {GET} method
         * @name ozelden.services.UserSettingService#getProfile
         * @methodOf ozelden.services.UserSettingService
         *
         * @description get selected user`s profile.
         */
        function getProfile() {
            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.profile(),
                headers: headers
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }


        /**
         * @ngdoc {GET} method
         * @name ozelden.services.UserSettingService#getUserClassList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description get selected user`s class list.
         */
        function getUserClassList() {
            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.userClassList(),
                headers: headers
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {GET} method
         * @name ozelden.services.UserSettingService#getUserLectureList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description get selected user`s lecture list.
         * @param {Object} params - holds the user id and response type.
         */
        function getUserLectureList(params) {
            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.userLecturesList(),
                headers: headers,
                params: params
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {GET} method
         * @name ozelden.services.UserSettingService#getSuitabilitySchedule
         * @methodOf ozelden.services.UserSettingService
         *
         * @description get selected user`s suitability schedule.
         */
        function getSuitabilitySchedule() {
            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.suitabilitySchedule(),
                headers: headers
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {PUT} method
         * @name ozelden.services.UserSettingService#updateProfile
         * @methodOf ozelden.services.UserSettingService
         *
         * @description update selected user`s profile info.
         * @param {Object} data - holds the profile data.
         */
        function updateProfile(data) {
            var deferred = $q.defer();

            $http({
                method: 'PUT',
                url: VocabularyService.profile(),
                headers: headers,
                data: data
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {PUT} method
         * @name ozelden.services.UserSettingService#updateSuitabilitySchedule
         * @methodOf ozelden.services.UserSettingService
         *
         * @description update selected user`s suitability schedule.
         * @param {Object} data - holds the user`s suitability settings.
         */
        function updateSuitabilitySchedule(data) {
            var deferred = $q.defer();

            $http({
                method: 'PUT',
                url: VocabularyService.suitabilitySchedule(),
                headers: headers,
                data: data
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {PUT} method
         * @name ozelden.services.UserSettingService#updateUserClass
         * @methodOf ozelden.services.UserSettingService
         *
         * @description update selected user`s given class.
         * @param {Object} data - holds the class data.
         */
        function updateUserClass(data) {
            var deferred = $q.defer();

            $http({
                method: 'PUT',
                url: VocabularyService.userClassList(),
                headers: headers,
                data: data
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {PUT} method
         * @name ozelden.services.UserSettingService#uploadProfilePicture
         * @methodOf ozelden.services.UserSettingService
         *
         * @description upload picture of selected user.
         * @param {Object} data - holds the picture data.
         */
        function uploadProfilePicture(data) {
            var deferred = $q.defer();

            $http({
                method: 'PUT',
                url: VocabularyService.uploadProfilePicture(),
                headers: headers,
                data: data
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {DELETE} method
         * @name ozelden.services.UserSettingService#removeClassFromUserClassList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description remove selected class from user`s class list.
         * @param {Object} data - holds the selected class`s id.
         */
        function removeClassFromUserClassList(data) {
            var deferred = $q.defer();

            $http({
                method: 'DELETE',
                url: VocabularyService.userClassList(),
                headers: headers,
                data: data
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {DELETE} method
         * @name ozelden.services.UserSettingService#removeLectureFromUserLectureList
         * @methodOf ozelden.services.UserSettingService
         *
         * @description remove selected lecture from user`s lecture list.
         * @param {Object} data - holds the selected lecture`s data.
         */
        function removeLectureFromUserLectureList(data) {
            var deferred = $q.defer();

            $http({
                method: 'DELETE',
                url: VocabularyService.userLecturesList(),
                headers: headers,
                data: data
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UserSettingService#$$fetchSuccessResponse
         * @methodOf ozelden.services.UserSettingService
         * @description call when response is success.
         */
        function $$fetchSuccessResponse(result, deferred) {
            if (result.status === "success") {
                deferred.resolve(result);
            } else {
                deferred.reject(result);
            }
        }

        /** POST METHODS **/
        this.addToUserClassList = addToUserClassList;
        this.addToUserLectureList = addToUserLectureList;
        /** GET METHODS **/
        this.getProfile = getProfile;
        this.getUserClassList = getUserClassList;
        this.getUserLectureList = getUserLectureList;
        this.getSuitabilitySchedule = getSuitabilitySchedule;
        /** PUT METHODS **/
        this.updateProfile = updateProfile;
        this.updateSuitabilitySchedule = updateSuitabilitySchedule;
        this.updateUserClass = updateUserClass;
        this.uploadProfilePicture = uploadProfilePicture;
        /** DELETE METHODS **/
        this.removeClassFromUserClassList = removeClassFromUserClassList;
        this.removeLectureFromUserLectureList = removeLectureFromUserLectureList;
        return this;
    }

    angular.module('ozelden.services').factory('UserSettingService', UserSettingService);
})();