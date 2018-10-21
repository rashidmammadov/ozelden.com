(function () {
    'use strict';

    function DataService($q, $http, VocabularyService) {

        /**
         * @ngdoc method
         * @name ozelden.services.DataService#get
         * @methodOf ozelden.services.SearchService
         *
         * @description returns result of given data.
         * @param {Object} params - holds the data params.
         */
        function get(params) {
            var deferred = $q.defer();

            $http({
                url: VocabularyService.data(),
                method: 'GET',
                params: params
            }).then(function (response) {
                var result = response.data;
                if (result.status === 'success') {
                    deferred.resolve(result.data);
                } else {
                    deferred.reject(result.failure);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        this.get = get;
        return this;
    }

    angular.module('ozelden.services').factory('DataService', DataService);
}());