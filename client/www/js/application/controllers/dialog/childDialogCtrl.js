(function () {
    'use strict';

    function ChildDialogCtrl($scope, $mdDialog, $state, locals) {
        var self = this;

        $scope.operation = locals.type;
        $scope.data = locals.data;

        if ($scope.data) {
            $scope.file = $scope.data.picture;
            self.name = $scope.data.name;
            self.surname = $scope.data.surname;
            self.sex = $scope.data.sex;
            self.birthDate = $scope.data.birthDate;
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:ChildDialogCtrl#upload
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
         * @name ozelden.controllers.controllers:ChildDialogCtrl#selectFile
         * @description open select file dialog when click button.
         */
        function selectFile() {
            $("#file").click();
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:ChildDialogCtrl#confirm
         * @description confirm child.
         */
        function confirm() {
            var file, fileType;
            if ($scope.file && $scope.name) {
                file = $scope.name.split('.');
                fileType = file[file.length - 1];
            }
            var data = {
                picture: ($scope.file && $scope.name) ? {base64: $scope.file, fileType: fileType} : null,
                name: self.name,
                surname: self.surname,
                sex: self.sex,
                birthDate: self.birthDate
            };
            $mdDialog.hide(data);
        }

        this.cancel = $mdDialog.cancel;
        this.confirm = confirm;
        $scope.upload = upload;
        this.selectFile = selectFile;
    }

    angular.module('ozelden.controllers').controller('ChildDialogCtrl', ChildDialogCtrl);
}());