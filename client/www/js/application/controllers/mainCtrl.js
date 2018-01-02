(function () {
    'use strict';

    function MainCtrl($scope) {
        var self = this;
        this.test;

        function change(){
            self.test = "ozelden.com";
        }

        this.change = change;
    }

    angular.module('ozelden.controllers').controller('MainCtrl', MainCtrl);
}());