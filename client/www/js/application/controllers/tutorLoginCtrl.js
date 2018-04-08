(function () {
    'use strict';

    function TutorLoginCtrl($rootScope, $state, $http, VocabularyService) {
        var self = this;

        this.loading = false;

        this.warningMessage;

        this.email;
        this.password;

        this.name;
        this.surname;
        this.dateOfBirth;
        this.sex;
        this.setEmail;
        this.telephone;
        this.setPassword;
        this.confirmPassword;
        this.terms = false;

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:tutorLoginCtrl#checkValidInput
         * @description Switch register button state between disable and enable.
         */
        function checkValidInput() {
            if(!self.name || !self.surname || !self.dateOfBirth || !self.sex || !self.setEmail || !self.setPassword || !self.confirmPassword
                || self.setPassword!=self.confirmPassword || self.setPassword<6 || self.terms==false){
                return true;
            }else{
                return false;
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:tutorLoginCtrl#login
         * @description If values are correct set user`s session and redirect to tutor dashboard.
         */
        function login() {
            self.loading = true;
            $http({
                method: 'GET',
                url: VocabularyService.tutorLogin(),
                params: {
                    email: self.email,
                    password: self.password
                }
            }).then(function (response) {
                var result = response.data;
                if(result.status === 'success') {
                    self.loginWarningMessage = "";
                    $rootScope.user = result.user;
                    $state.go('ozelden.tutor.dashboard');
                } else {
                    self.loginWarningMessage = result.message;
                }
                self.loading = false;
            }, function () {
                self.loginWarningMessage = "SOMETHING_WENT_WRONG";
                self.loading = false;
            })
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:tutorLoginCtrl#register
         * @description Send request for register tutor.
         */
        function register() {
            self.loading = true;
            $http({
                method: 'POST',
                url: VocabularyService.tutorRegister(),
                headers: {'Content-Type': 'application/json'},
                data: {
                    name: self.name,
                    surname: self.surname,
                    birthDate: self.dateOfBirth.getTime(),
                    email: self.setEmail,
                    password: self.setPassword,
                    sex: self.sex,
                    telephone: self.telephone
                }
            }).then(function (response){
                var result = response.data;
                if (result.status == 'success'){
                    self.warningMessage = "";
                    $rootScope.user = result.user;
                    $state.go('ozelden.tutor.dashboard');
                } else {
                    self.warningMessage = result.message;
                }
                self.loading = false;
            }, function (rejection) {
                self.warningMessage = "SOMETHING_WENT_WRONG";
                self.loading = false;
            });
        }

        this.checkValidInput = checkValidInput;
        this.login = login;
        this.register = register;
    }

    angular.module('ozelden.controllers').controller('TutorLoginCtrl', TutorLoginCtrl);
}());