(function () {
    'use strict';

    function UserProfileCtrl($rootScope, $scope, $mdDialog, CookieService) {
        var self = this;

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
            })
        }

        this.upload = upload;
    }

    angular.module('ozelden.controllers').controller('UserProfileCtrl', UserProfileCtrl);
}());