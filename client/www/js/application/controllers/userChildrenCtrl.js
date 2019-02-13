(function () {
    'use strict';

    function UserChildrenCtrl($rootScope, $mdDialog, $filter, ChildService, NotificationService) {
        var self = this;
        $rootScope.loadingOperation = true;

        this.childList = [];

        function getChildList() {
            ChildService.getUserChildren().then(function (result) {
                $rootScope.loadingOperation = false;
                self.childList = result.data;
            }, function () {
                $rootScope.loadingOperation = false;
            });
        }

        getChildList();

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserChildrenCtrl#openChildrenDialog
         * @description Open dialog with given operation and data.

         * @param {String} operation - holds the operation name.
         * @param {Object=} data - holds the child data.
         */
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
                }).then(function (child) {
                    if (operation === 'create') {
                        $$createChild(child);
                    } else if (operation === 'edit') {
                        $$updateChild(child);
                    }
                })
            } else {
                var confirm = $mdDialog.confirm()
                    .title($filter('translate')('REMOVE_CHILD_TITLE'))
                    .textContent($filter('translate')('REMOVE_CHILD_CONTENT'))
                    .ariaLabel('Remove Child')
                    .ok($filter('translate')('CONFIRM'))
                    .cancel($filter('translate')('CANCEL'));

                $mdDialog.show(confirm).then(function() {
                    $rootScope.loadingOperation = true;
                    $$removeChild(data.childId);
                }, function () {
                    $rootScope.loadingOperation = false;
                });
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserChildrenCtrl#$$createChild
         * @description Add new child to user`s child list.

         * @param {Object} child - holds the child data.
         */
        function $$createChild(child) {
            $rootScope.loadingOperation = true;
            ChildService.addNewChild(child).then(function (result) {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(result.message));
                getChildList();
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('SOMETHING_WENT_WRONG'));
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserChildrenCtrl#$$removeChild
         * @description Remove the given child.

         * @param {Integer} childId - holds the child data.
         */
        function $$removeChild(childId) {
            ChildService.removeChild({childId: childId}).then(function (result) {
                $rootScope.loadingOperation = false;
                if (result.status === 'success') {
                    self.childList = self.childList.filter(function (d) { return d.childId !== childId; });
                    NotificationService.showMessage($filter('translate')(result.message));
                }
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('SOMETHING_WENT_WRONG_WHILE_REMOVING_CHILD'));
            })
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserChildrenCtrl#$$updateChild
         * @description Update the given child data.

         * @param {Object} child - holds the child data.
         */
        function $$updateChild(child) {
            $rootScope.loadingOperation = true;
            ChildService.updateChild(child).then(function (result) {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(result.message));
                getChildList();
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('SOMETHING_WENT_WRONG'));
            });
        }

        this.openChildrenDialog = openChildrenDialog;
    }

    angular.module('ozelden.controllers').controller('UserChildrenCtrl', UserChildrenCtrl);
}());