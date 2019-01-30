(function () {
    'use strict';

    function UploadFileDialogCtrl($scope, $mdDialog) {

        var self = this;
        this.loading = false;
        $scope.file = null;

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UploadFileDialogCtrl#confirm
         * @description confirm uploaded file.
         */
        function confirm() {
            var file = $scope.name.split('.');
            var fileType = file[file.length - 1];
            $mdDialog.hide({base64: $scope.file, fileType: fileType});
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UploadFileDialogCtrl#upload
         * @description upload selected file.
         */
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
                            $scope.file = data.target.result;
                            $scope.$apply();
                        }
                    }
                };
            } else {
                self.loading = false;
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UploadFileDialogCtrl#selectFile
         * @description open select file dialog when click button.
         */
        function selectFile() {
            $("#file").click();
        }

        this.cancel = $mdDialog.cancel;
        this.confirm = confirm;
        $scope.upload = upload;
        this.selectFile = selectFile;
    }

    angular.module('ozelden.controllers').controller('UploadFileDialogCtrl', UploadFileDialogCtrl);
}());