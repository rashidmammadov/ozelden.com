(function () {
    'use strict';

    function SearchService($http, $q, VocabularyService) {

        /**
         * @ngdoc method
         * @name ozelden.services.SearchService#tutorSearch
         * @methodOf ozelden.services.SearchService
         *
         * @description returns result of tutor search.
         * @param {Object} params - holds the search params.
         */
        function tutorSearch(params) {
            var deferred = $q.defer();

            $http({
                url: VocabularyService.search(),
                method: 'GET',
                params: {
                    act: 'tutorSearch',
                    offset: params.offset,
                    limit: 2
                }
            }).then(function (response) {
                var result = response.data;
                if (result.success) {
                    deferred.resolve(result.data);
                } else {
                    deferred.reject(result.failure);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        this.tutorSearch = tutorSearch;
        return this;
    }

    angular.module('ozelden.services').factory('SearchService', SearchService);
})();