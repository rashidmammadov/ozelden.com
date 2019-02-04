(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:UserSearchCtrl
     * @description Controller for the main page view.
     */
    function UserSearchCtrl($rootScope, $scope, $state, $translate, SearchService) {
        var self = this;

        $rootScope.loadingOperation = false;
        this.lectures = $rootScope.lectures;
        this.regions = $rootScope.regions;
        this.searchResult = [];

        /**
         * @ngdoc method
         * @description Get default data.
         */
        SearchService.get({type: 'tutor'}).then(function (d) {
            self.searchResult = d.data;
        });

    }

    angular.module('ozelden.controllers').controller('UserSearchCtrl', UserSearchCtrl);
}());