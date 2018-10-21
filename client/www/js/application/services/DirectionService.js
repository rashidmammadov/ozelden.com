(function () {
    'use strict';

    function DirectionService($rootScope) {

        function apiUri() {
            return "http://localhost:8000/api/v1/";
        }

        this.apiUri = apiUri;

        return this;
    }

    angular.module('ozelden.services').factory('DirectionService', DirectionService);
}());