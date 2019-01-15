(function () {
    'use strict';

    function ClassCtrl($rootScope, $mdDialog) {
        var self = this;
        $rootScope.loadingOperation = false;

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
            }).then(function(){}, function () {});
        };

        this.openClassDialog = openClassDialog;
    }

    angular.module('ozelden.controllers').controller('ClassCtrl', ClassCtrl);
}());