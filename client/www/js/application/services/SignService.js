(function () {
    'use strict';

    function SignService($http, $q, VocabularyService, CookieService) {

        /**
         * @ngdoc method
         * @name ozelden.services.SignService#refreshUser
         * @methodOf ozelden.services.TutorService
         *
         * @description get current user`s info by token.
         * @param {String} token - holds the user token.
         */
        function refreshUser(token) {
            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.refreshUser(),
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + CookieService.getUser().remember_token
                }
            }).then(function (response) {
                var result = response.data;
                if (result.status === "success") {
                    deferred.resolve(result);
                } else {
                    deferred.reject(result);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.SignService#_in
         * @methodOf ozelden.services.TutorService
         *
         * @description login user.info.
         */
        function _in(data) {
            var deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.userLogin(),
                headers: { 'Content-Type': 'application/json' },
                data: data
            }).then(function (response) {
                var result = response.data;
                if (result.status === "success") {
                    deferred.resolve(result);
                } else {
                    deferred.reject(result);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.SignService#up
         * @methodOf ozelden.services.TutorService
         *
         * @description register user with given data.
         * @param {Object} data - holds the user`s given info.
         */
        function up(data) {
            var deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.userRegister(),
                headers: { 'Content-Type': 'application/json' },
                data: data
            }).then(function (response) {
                var result = response.data;
                if (result.status === "success") {
                    deferred.resolve(result);
                } else {
                    deferred.reject(result);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.SignService#out
         * @methodOf ozelden.services.TutorService
         *
         * @description send request to logout user from application.
         */
        function out() {
            var deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.userLogout(),
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + CookieService.getUser().remember_token
                }
            }).then(function (response) {
                var result = response.data;
                if (result.status === "success") {
                    deferred.resolve(result);
                } else {
                    deferred.reject(result);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        this.refreshUser = refreshUser;
        this._in = _in;
        this.up = up;
        this.out = out;
        return this;
    }

    angular.module('ozelden.services').factory('SignService', SignService);
})();