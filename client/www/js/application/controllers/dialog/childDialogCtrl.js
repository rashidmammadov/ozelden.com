(function () {
    'use strict';

    function ChildDialogCtrl($scope, $mdDialog, $state, locals, CookieService, UtilityService) {
        var self = this;
        var userId = CookieService.getUser() && CookieService.getUser().id;

        $scope.operation = locals.type;
        $scope.data = locals.data;

        function confirm() {
            $mdDialog.hide();
        }

        this.cancel = $mdDialog.cancel;
        this.confirm = confirm;
    }

    angular.module('ozelden.controllers').controller('ChildDialogCtrl', ChildDialogCtrl);
}());