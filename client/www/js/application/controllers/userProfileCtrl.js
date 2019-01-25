(function () {
    'use strict';

    function UserProfileCtrl($rootScope, CookieService) {
        var self = this;

        this.userCookie = CookieService.getUser();
        this.personal = $rootScope.user;
    }

    angular.module('ozelden.controllers').controller('UserProfileCtrl', UserProfileCtrl);
}());