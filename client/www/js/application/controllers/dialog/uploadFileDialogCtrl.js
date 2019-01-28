(function () {
    'use strict';

    function UploadFileDialogCtrl($scope, $mdDialog, locals) {

        var self = this;
        this.loading = false;
        this.file = null;
        $scope.base64 = null;

        function confirm() {
            var file = $scope.name.split('.');
            var fileType = file[file.length - 1];
            $mdDialog.hide({base64: self.file, fileType: fileType});
        }

        function upload() {
            self.loading = true;
            var reader = new FileReader();
            var file = $('#file')[0];
            if (file && file.files[0]) {
                reader.readAsDataURL(file.files[0]);
                $scope.name = file.files[0].name;
                reader.onprogress = function(data) {
                    if (data.lengthComputable) {
                        var progress = parseInt( ((data.loaded / data.total) * 100), 10 );
                        if (progress === 100) {
                            self.loading = false;
                            var d = self.file = data.target.result;
                            $scope.base64 = d.replace(/^[^,]*,/, '');
                        }
                        $scope.$apply();
                    }
                };
            }
        }

        this.cancel = $mdDialog.cancel;
        this.confirm = confirm;
        $scope.upload = upload;
    }

    angular.module('ozelden.controllers').controller('UploadFileDialogCtrl', UploadFileDialogCtrl);
}());