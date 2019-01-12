(function () {
    'use strict';

    function ClassCtrl($rootScope, $state, CookieService) {

        var self = this;
        this.userCookie = CookieService.getUser();

        if (!self.userCookie) {
            $state.go('ozelden.user.sign');
        }
    }

    angular.module('ozelden.controllers').controller('ClassCtrl', ClassCtrl);
}());