(function () {
    'use strict';

    function DirectionService($rootScope) {
        var self = this;

        function serverUri() {
            return "http://localhost/ozelden.com/server/index.php/";
        }

        this.serverUri = serverUri;

        return this;
    }

    angular.module('ozelden.services').factory('DirectionService', DirectionService);
}());