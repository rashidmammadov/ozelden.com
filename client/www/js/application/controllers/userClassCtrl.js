(function () {
    'use strict';

    function UserClassCtrl($rootScope, $mdDialog, UserSettingService) {
        var self = this;
        $rootScope.loadingOperation = true;

        UserSettingService.getUserClassList().then(function(result) {
            result;
            $rootScope.loadingOperation = false;
        }, function() {
            $rootScope.loadingOperation = false;
        });

        function openClassDialog(event, operation, data) {
            $mdDialog.show({
                controller: 'ClassDialogCtrl',
                controllerAs: 'Class',
                templateUrl: 'html/controllers/dialog/class.html',
                locals: {
                    type: operation,
                    data: data
                },
                targetEvent: event,
                clickOutsideToClose: true
            }).then(function(params) {
                if (operation === 'create') {
                    UserSettingService.addToUserClassList(params).then(function (result) {
                        result;
                    }, function (failure) {
                        failure;
                    });
                }
            });
        };

        this.openClassDialog = openClassDialog;
    }

    angular.module('ozelden.controllers').controller('UserClassCtrl', UserClassCtrl);
}());