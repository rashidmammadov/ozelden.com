(function () {
    'use strict';

    function LocationService(DirectionService) {

        function getCities() {
            return DirectionService.serverUri() + 'location/getCities'
        }

        this.getCities = getCities;
        return this;
    }

    angular.module('ozelden.services').factory('LocationService', LocationService);
}());