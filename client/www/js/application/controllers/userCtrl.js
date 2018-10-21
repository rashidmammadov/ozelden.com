(function () {
    'use strict';

    function UserCtrl($rootScope, $state, CookieService) {

        var self = this;
        this.userCookie = CookieService.getUser();

        if (!self.userCookie) {
            $state.go('ozelden.user.sign');
        }
    }

    angular.module('ozelden.controllers').controller('UserCtrl', UserCtrl);
}());