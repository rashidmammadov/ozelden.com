(function () {
    'use strict';

    function UploadFileDialogCtrl($scope, $mdDialog, locals) {

        var self = this;
        $scope.base64 = null;

        function confirm() {

        }

        function upload() {
            var reader = new FileReader();
            var file = $('#file')[0];
            if (file && file.files[0]) {
                reader.readAsDataURL(file.files[0]);

                $scope.name = file.files[0].name;

                reader.onprogress = function(data) {
                    if (data.lengthComputable) {
                        self.loading = true;
                        var progress = parseInt( ((data.loaded / data.total) * 100), 10 );
                        if (progress == 100){
                            var data = data.target.result;
                            $scope.base64 = data.replace(/^[^,]*,/, '');
                        }
                        $scope.$apply();
                    }
                };
            }
        }

        this.confirm = confirm;
        this.upload = upload;
    }

    angular.module('ozelden.controllers').controller('UploadFileDialogCtrl', UploadFileDialogCtrl);
}());