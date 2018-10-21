(function () {
    'use strict';

    function UserDashboardCtrl($state, CookieService) {

        if (!CookieService.getUser()) {
            $state.go('ozelden.user.sign');
        }
    }

    angular.module('ozelden.controllers').controller('UserDashboardCtrl', UserDashboardCtrl);
})();