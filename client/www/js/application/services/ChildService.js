(function () {
    'use strict';

    function ChildService($http, $q, VocabularyService, CookieService) {

        var headers = {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + CookieService.getUser().remember_token
        };

        /**
         * @ngdoc {POST} method
         * @name ozelden.services.ChildService#addNewChild
         * @methodOf ozelden.services.ChildService
         *
         * @description add new child with given child data.
         * @param {Object} data - holds the child data.
         */
        function addNewChild(data) {
            var deferred = $q.defer();

            $http({
                method: 'POST',
                url: VocabularyService.child_(),
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
         * @ngdoc {GET} method
         * @name ozelden.services.ChildService#getUserChildren
         * @methodOf ozelden.services.ChildService
         *
         * @description get current user`s children.
         */
        function getUserChildren() {
            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: VocabularyService.child_(),
                headers: headers
            }).then(function (response) {
                $$fetchSuccessResponse(response.data, deferred);
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc {DELETE} method
         * @name ozelden.services.ChildService#removeChild
         * @methodOf ozelden.services.ChildService
         *
         * @description remove given child.
         * @param {Object} data - holds the child id.
         */
        function removeChild(data) {
            var deferred = $q.defer();

            $http({
                method: 'DELETE',
                url: VocabularyService.child_(),
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
         * @name ozelden.services.ChildService#updateChild
         * @methodOf ozelden.services.ChildService
         *
         * @description update given child.
         * @param {Object} data - holds the child data.
         */
        function updateChild(data) {
            var deferred = $q.defer();

            $http({
                method: 'PUT',
                url: VocabularyService.child_(),
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
         * @name ozelden.services.ChildService#$$fetchSuccessResponse
         * @methodOf ozelden.services.ChildService
         * @description call when response is success.
         */
        function $$fetchSuccessResponse(result, deferred) {
            if (result.status === 'success') {
                deferred.resolve(result);
            } else {
                deferred.reject(result);
            }
        }

        this.addNewChild = addNewChild;
        this.getUserChildren = getUserChildren;
        this.removeChild = removeChild;
        this.updateChild = updateChild;
        return this;
    }

    angular.module('ozelden.services').factory('ChildService', ChildService);
})();