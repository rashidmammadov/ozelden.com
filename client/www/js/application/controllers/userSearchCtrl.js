(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:UserSearchCtrl
     * @description Controller for the main page view.
     */
    function UserSearchCtrl($rootScope, $scope, $state, $translate, CookieService, DataService) {
        var self = this;

        $rootScope.loadingOperation = true;
        this.lectures;
        this.regions;

        /**
         * @ngdoc method
         * @description Get default data.
         */
        DataService.get({regions: true, lectures: true}).then(function (result){
            result.lectures && (self.lectures = result.lectures);
            result.regions && (self.regions = result.regions);
            $rootScope.loadingOperation = false;
        },function(rejection){
            $rootScope.loadingOperation = false;
        });

        this.searchResult = [];

        for (var i=1; i<20; i++) {
            self.searchResult.push({
                'id': i,
                'name': i + 'Rashid',
                'surname': 'Mammadov',
                'birthDate':  713134324000,
                'email': 'reshidmemmedov@gmail.com',
                'sex': 'mars',
                'telephone': '05079708807',
                'image': null,
                'registerDate': 713134324000,
                'average': 9,
                'expression': 0,
                'discipline': 4.3,
                'contact': 8.5,
                'regions': 'Izmir'
            })
        }
    }

    angular.module('ozelden.controllers').controller('UserSearchCtrl', UserSearchCtrl);
}());