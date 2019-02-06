(function () {
    'use strict';

    function UserCtrl($scope, $state, CookieService) {

        var self = this;
        $scope.state = $state;
        this.userCookie = CookieService.getUser();
        this.toggleLeft = $scope.$parent.toggleLeft;

        if (!self.userCookie) {
            $state.go('ozelden.user.sign');
        }

        function link(ref) {
            $state.go(ref);
            self.toggleLeft();
        }

        this.link = link;
    }

    angular.module('ozelden.controllers').controller('UserCtrl', UserCtrl);
}());