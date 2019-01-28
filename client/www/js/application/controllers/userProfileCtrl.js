(function () {
    'use strict';

    function UserProfileCtrl($rootScope, $scope, $mdDialog, CookieService, UserSettingService) {
        var self = this;

        this.picture = null;

        this.userCookie = CookieService.getUser();
        this.personal = $rootScope.user;

        function upload() {
            $mdDialog.show({
                controller: 'UploadFileDialogCtrl',
                controllerAs: 'File',
                templateUrl: 'html/controllers/dialog/upload.html',
                locals: {},
                targetEvent: event,
                clickOutsideToClose: true
            }).then(function (result) {
                self.picture = result.base64;
                UserSettingService.uploadProfilePicture(result);
            }, function () {});
        }

        this.upload = upload;
    }

    angular.module('ozelden.controllers').controller('UserProfileCtrl', UserProfileCtrl);
}());