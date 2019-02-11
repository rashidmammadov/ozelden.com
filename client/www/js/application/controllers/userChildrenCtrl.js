(function () {
    'use strict';

    function UserChildrenCtrl($mdDialog) {
        var self = this;

        this.childList = [{
            childId: 1,
            name: 'Rashid',
            surname: 'Mammadov',
            classes: [{
                className: 'my first class'
            },{
                className: 'my second class'
            },{
                className: 'my third class'
            }]
        },{
            childId: 1,
            name: 'Rashid',
            surname: 'Mammadov'
        }];

        function openChildrenDialog(operation, data) {
            if (operation !== 'remove') {
                $mdDialog.show({
                    controller: 'ChildDialogCtrl',
                    controllerAs: 'Child',
                    templateUrl: 'html/controllers/dialog/child.html',
                    locals: {
                        type: operation,
                        data: data
                    },
                    clickOutsideToClose: true
                })
            }
        }

        this.openChildrenDialog = openChildrenDialog;
    }

    angular.module('ozelden.controllers').controller('UserChildrenCtrl', UserChildrenCtrl);
}());