(function () {
    'use strict';

    function ClassCtrl($mdDialog) {

        var self = this;

        var originatorEv;

        this.openMenu = function($mdMenu, ev) {
            originatorEv = ev;
            $mdMenu.open(ev);
        };

        this.title = "Lise - Matematik";
    }

    angular.module('ozelden.controllers').controller('ClassCtrl', ClassCtrl);
}());