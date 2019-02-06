(function () {
    'use strict';

    function UserProfileCtrl($rootScope, $filter, $scope, $mdDialog, UserSettingService, NotificationService) {
        var self = this;

        $rootScope.loadingOperation = true;

        this.regions = $rootScope.regions;
        this.personal = $rootScope.user;
        this.picture = 'img/default/'.concat(self.personal.sex === 'venus' ? 'female-user.png' : 'male-user.png');
        this.profile = {};

        UserSettingService.getProfile().then(function(result) {
            $rootScope.loadingOperation = false;
            if (result.status === 'success') {
                self.profile = result.data;
                self.picture = $$setProfilePicture(self.profile.picture);
            }
        }, function () {
            $rootScope.loadingOperation = false;
            NotificationService.showMessage('SOMETHING_WENT_WRONG');
        });
        
        function updateContact() {
            $rootScope.loadingOperation = true;
            var data = {
                phone: self.profile.phone,
                city: self.profile.city,
                district: self.profile.district,
                address: self.profile.address
            };
            UserSettingService.updateProfile(data).then(function(result) {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(result.message));
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('SOMETHING_WENT_WRONG'));
            });
        }

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
            UserSettingService.uploadProfilePicture(data).then(function(result) {
                $rootScope.loadingOperation = false;
                self.picture = data.base64;
                NotificationService.showMessage($filter('translate')(result.message));
            }, function () {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('SOMETHING_WENT_WRONG'));
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserProfileCtrl#$$setProfilePicture
         * @description prepare picture to visible.
         * @param {String} originalUrl - holds the picture url
         */
        function $$setProfilePicture(originalUrl) {
            return originalUrl ? originalUrl : self.picture;
        }

        this.updateContact = updateContact;
        this.upload = upload;
    }

    angular.module('ozelden.controllers').controller('UserProfileCtrl', UserProfileCtrl);
}());