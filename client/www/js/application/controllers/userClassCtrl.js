(function () {
    'use strict';

    function UserClassCtrl($rootScope, $filter, $mdDialog, CookieService, UserSettingService, NotificationService) {
        var self = this;
        var userId = CookieService.getUser() && CookieService.getUser().id;
        $rootScope.loadingOperation = true;

        this.classList = [];
        this.lectures = [];
        this.regions = [];

        /**
         * @ngdoc request
         * @description Get user`s lectures list.
         */
        UserSettingService.getUserLectureList({userId: userId, average: true}).then(function(result) {
            if (result.status === 'success') { self.lectures = result.data; }
        });

        /**
         * @ngdoc request
         * @description Get user`s regions list.
         */
        UserSettingService.getSuitabilitySchedule(userId).then(function (result) {
            if (result.status === 'success') { self.regions = result.data.region; }
        });

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
         * @param {String} operation - select the operation type ['create', 'edit', 'remove']
         * @param {Object=} data - holds the class data if exist
         */
        function openClassDialog(operation, data) {
            if (operation !== 'remove') {
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
                        tutors: tutors,
                        lectures: self.lectures,
                        regions: self.regions
                    },
                    clickOutsideToClose: true
                }).then(function (params) {
                    $rootScope.loadingOperation = true;
                    if (operation === 'create') {
                        $$createClass(params);
                    } else if (operation === 'edit') {
                        $$updateClass(params);
                    }
                }, function () {});
            } else {
                var confirm = $mdDialog.confirm()
                    .title($filter('translate')('REMOVE_CLASS_TITLE'))
                    .textContent($filter('translate')('REMOVE_CLASS_CONTENT'))
                    .ariaLabel('Remove Class')
                    .ok($filter('translate')('CONFIRM'))
                    .cancel($filter('translate')('CANCEL'));

                $mdDialog.show(confirm).then(function() {
                    $rootScope.loadingOperation = true;
                    $$removeClass(data.classId);
                }, function () {});
            }
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
         * @name ozelden.controllers.controllers:UserClassCtrl#$$removeClass
         * @description Remove the given class.

         * @param {Integer} classId - holds the class data.
         */
        function $$removeClass(classId) {
            UserSettingService.removeClassFromUserClassList({classId: classId}).then(function (result) {
                $rootScope.loadingOperation = false;
                if (result.status === 'success') {
                    self.classList = self.classList.filter(function (d) { return d.classId !== classId; });
                    NotificationService.showMessage($filter('translate')(result.message));
                }
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('SOMETHING_WENT_WRONG_WHILE_REMOVING_CLASS'));
            })
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