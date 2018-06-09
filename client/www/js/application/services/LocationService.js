(function () {
    'use strict';

    function LocationService(DirectionService) {

        function getCities() {
            return DirectionService.serverUri() + 'location/getCities'
        }

        function getLectures() {
            return DirectionService.serverUri() + 'location/getLectures'
        }

        this.getCities = getCities;
        this.getLectures = getLectures;
        return this;
    }

    angular.module('ozelden.services').factory('LocationService', LocationService);
}());