(function () {
    'use strict';

    function SearchService($http, $q, VocabularyService, CookieService) {

        var headers = {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + CookieService.getUser().remember_token
        };

        /**
         * @ngdoc method
         * @name ozelden.services.SearchService#get
         * @methodOf ozelden.services.SearchService
         *
         * @description returns result of tutor search.
         * @param {Object} params - holds the search params.
         */
        function get(params) {
            var deferred = $q.defer();

            $http({
                url: VocabularyService.search(),
                method: 'GET',
                headers: headers,
                params: params
            }).then(function (response) {
                var result = response.data;
                if (result.status === 'success') {
                    deferred.resolve(result);
                } else {
                    deferred.reject(result);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        this.get = get;
        return this;
    }

    angular.module('ozelden.services').factory('SearchService', SearchService);
})();