(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:MainCtrl
     * @description Controller for the main page view.
     */
    function MainCtrl($scope, $translate) {
        var self = this;

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:MainCtrl#changeLangugage
         * @description This method changes application`s language.
         * @param lang: send selected language name.
         */
        function changeLanguage(lang){
            $translate.use(lang);
        }

        function register() {

        }

        this.changeLanguage = changeLanguage;
        this.register = register;
    }

    angular.module('ozelden.controllers').controller('MainCtrl', MainCtrl);
}());