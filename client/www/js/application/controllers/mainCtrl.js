(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:MainCtrl
     * @description Controller for the main page view.
     */
    function MainCtrl($scope, $state, $translate, $mdSidenav, user, SearchService) {
        var self = this;

        this.searchResult;
        this.selectedLanguage = $translate.preferredLanguage();
        $scope.toggleLeft = buildToggler('left');

        SearchService.tutorSearch().then(function(result){
            self.searchResult = result;
        }, function(failure){

        });

        function buildToggler(componentId) {
            return function() {
                $mdSidenav(componentId).toggle();
            };
        }
        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:MainCtrl#changeLangugage
         * @description This method changes application`s language.
         * @param lang: send selected language name.
         */
        function changeLanguage(lang){
            $translate.use(lang);
        }

        this.changeLanguage = changeLanguage;
    }

    angular.module('ozelden.controllers').controller('MainCtrl', MainCtrl);
}());