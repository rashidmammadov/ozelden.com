(function () {
    'use strict';

    function UserClassCtrl($rootScope, $filter, $mdDialog, UserSettingService, NotificationService) {
        var self = this;
        $rootScope.loadingOperation = true;

        this.classList = [];
        this.lecturesList = [];

        /**
         * @ngdoc request
         * @description Get user`s class list.
         */
        function getClassList() {
            UserSettingService.getUserClassList().then(function(result) {
                $rootScope.loadingOperation = false;
                if (result.status === 'success') {
                    self.classList = result.data;
                }
            }, function() {
                $rootScope.loadingOperation = false;
            });
        }

        getClassList();

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserClassCtrl#openClassDialog
         * @description Add created class to class list.
         *
         * @param {String} operation - select the operation type ['create', 'edit']
         * @param {Object=} data - holds the class data if exist
         */
        function openClassDialog(operation, data) {
            var tutors = [{
                id: $rootScope.user.id,
                name: $rootScope.user.name + ' ' + $rootScope.user.surname
            }];
            $mdDialog.show({
                controller: 'ClassDialogCtrl',
                controllerAs: 'Class',
                templateUrl: 'html/controllers/dialog/class.html',
                locals: {
                    type: operation,
                    data: data,
                    tutors: tutors
                },
                targetEvent: event,
                clickOutsideToClose: true
            }).then(function(params) {
                $rootScope.loadingOperation = true;
                if (operation === 'create') {
                    $$createClass(params);
                } else if (operation === 'edit') {
                    $$updateClass(params);
                }
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserClassCtrl#$$createClass
         * @description Add class to list.

         * @param {Object=} params - holds the class data.
         */
        function $$createClass(params) {
            UserSettingService.addToUserClassList(params).then(function (result) {
                $rootScope.loadingOperation = false;
                if (result.status === 'success') {
                    NotificationService.showMessage($filter('translate')(result.message));
                    getClassList();
                }
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('SOMETHING_WHEN_WRONG_WHILE_ADDING_CLASS'));
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserClassCtrl#$$updateClass
         * @description Update the given class.

         * @param {Object=} params - holds the class data.
         */
        function $$updateClass(params) {
            UserSettingService.updateUserClass(params).then(function (result) {
                $rootScope.loadingOperation = false;
                if (result.status === 'success') {
                    NotificationService.showMessage($filter('translate')(result.message));
                    getClassList();
                }
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('SOMETHING_WENT_WRONG_WHILE_SAVING_CHANGES'));
            });
        }

        this.openClassDialog = openClassDialog;
    }

    angular.module('ozelden.controllers').controller('UserClassCtrl', UserClassCtrl);
}());