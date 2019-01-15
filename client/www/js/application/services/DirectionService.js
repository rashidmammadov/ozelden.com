(function () {
    'use strict';

    function DirectionService($rootScope) {

        // https://api.ozelden.com/api/v1/
        // http://localhost:8000/api/v1/
        function apiUri() {
            return "http://localhost:8000/api/v1/";
        }

        this.apiUri = apiUri;

        return this;
    }

    angular.module('ozelden.services').factory('DirectionService', DirectionService);
}());