(function () {
    'use strict';

    function UserProfileCtrl($rootScope, $filter, $scope, $mdDialog, UserSettingService, NotificationService) {
        var self = this;

        $rootScope.loadingOperation = false;

        this.picture = null;
        this.personal = $rootScope.user;

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserProfileCtrl#upload
         * @description Open upload picture dialog.
         */
        function upload() {
            $mdDialog.show({
                controller: 'UploadFileDialogCtrl',
                controllerAs: 'File',
                templateUrl: 'html/controllers/dialog/upload.html',
                locals: {},
                clickOutsideToClose: true
            }).then($$uploadPicture, function () {$rootScope.loadingOperation = false;});
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserProfileCtrl#$$uploadPicture
         * @description send request to upload picture.
         * @param {Object} data - holds the picture data
         */
        function $$uploadPicture(data) {
            $rootScope.loadingOperation = true;
            UserSettingService.uploadProfilePicture(data).then(function(d) {
                $rootScope.loadingOperation = false;
                self.picture = data.base64;
                NotificationService.showMessage($filter('translate')(d.message));
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage('SOMETHING_WENT_WRONG');
            });
        }

        this.upload = upload;
    }

    angular.module('ozelden.controllers').controller('UserProfileCtrl', UserProfileCtrl);
}());