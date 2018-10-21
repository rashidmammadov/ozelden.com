(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:MainCtrl
     * @description Controller for the main page view.
     */
    function MainCtrl($scope, $rootScope, $state, $translate, $mdSidenav, CookieService, user, SignService) {
        var self = this;

        //this.searchResult;
        this.selectedLanguage = $translate.preferredLanguage();
        $scope.toggleLeft = buildToggler('left');

        // TODO
        //SearchService.tutorSearch({offset: 0}).then(function(result){
        // self.searchResult = result;
        //}, function(failure){

        //});

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

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:MainCtrl#logut
         * @description Logout user from application.
         */
        function logout() {
            SignService.out({remember_token: CookieService.getUser().remember_token}).then(function (response) {
                if (response.status === 'success') {
                    CookieService.removeUser();
                    $rootScope.user = {};
                    $state.go('ozelden.user.sign');
                }
            })
        }

        this.changeLanguage = changeLanguage;
        this.logout = logout;
    }

    angular.module('ozelden.controllers').controller('MainCtrl', MainCtrl);
}());