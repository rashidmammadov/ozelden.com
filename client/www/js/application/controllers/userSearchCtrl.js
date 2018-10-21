(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:UserSearchCtrl
     * @description Controller for the main page view.
     */
    function UserSearchCtrl($scope, $state, $translate, CookieService, DataService) {
        var self = this;

        this.loading = true;
        this.lectures;
        this.regions;

        /**
         * @ngdoc method
         * @description Get default data.
         */
        DataService.get({regions: true, lectures: true}).then(function (response){
            response.lectures && (self.lectures = response.lectures);
            response.regions && (self.regions = response.regions);
            self.loading = false;
        },function(rejection){
            self.loading = false;
        });

        this.searchResult = [{
            'id': 1,
            'name': 'Rashid',
            'surname': 'Mammadov',
            'birthDate':  713134324000,
            'email': 'reshidmemmedov@gmail.com',
            'sex': 'mars',
            'telephone': '05079708807',
            'image': null,
            'registerDate': 713134324000,
            'average': 9,
            'expression': 9.1,
            'attention': 7.3,
            'contact': 8.5,
            'regions': 'Izmir'
        },{
            'id': 2,
            'name': 'Rashid',
            'surname': 'Mammadov',
            'birthDate':  713134324000,
            'email': 'reshidmemmedov@gmail.com',
            'sex': 'mars',
            'telephone': '05079708807',
            'image': null,
            'registerDate': 713134324000,
            'average': 9,
            'expression': 9.1,
            'attention': 7.3,
            'contact': 8.5,
            'regions': 'Izmir'
        }];
    }

    angular.module('ozelden.controllers').controller('UserSearchCtrl', UserSearchCtrl);
}());