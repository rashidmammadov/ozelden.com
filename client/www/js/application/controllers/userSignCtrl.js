(function () {
    'use strict';

    function UserSignCtrl($rootScope, $scope, $state, $http, $filter, CookieService, NotificationService, SignService) {
        var self = this;

        if (CookieService.getUser()) {
            $state.go('ozelden.user.dashboard');
        }

        $scope.tabindex = 0;
        this.loading = false;

        this.signInEmail = "";
        this.signInPassword = "";

        this.type = "tutor";
        this.name = "";
        this.surname = "";
        this.birthDate = new Date();
        this.sex = "venus";
        this.email = "";
        this.password = "";
        this.passwordConfirm = "";
        this.terms = false;

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserSignCtrl#checkValidInput
         * @description Switch register button state between disable and enable.
         */
        function checkValidInput() {
            if(!self.name || !self.surname || !self.birthDate|| !self.sex || !self.email || !self.password || !self.passwordConfirm
                || self.password!=self.passwordConfirm || self.password < 6 || self.terms==false){
                return true;
            }else{
                return false;
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserSignCtrl#_in
         * @description Send request for log in user.
         */
        function _in() {
            self.loading = true;
            var data = {
                email: self.signInEmail,
                password: self.signInPassword
            };

            SignService._in(data).then(function (result) {
                if (result.status === 'success'){
                    NotificationService.showMessage($filter("translate")(result.message));
                    $rootScope.user = result.data;
                    CookieService.setUser($rootScope.user, "3-m");
                    $state.go("ozelden.user.dashboard");
                } else {
                    NotificationService.showMessage($filter("translate")(result.message));
                }
                self.loading = false;
            }, function ($rejection) {
                NotificationService.showMessage($filter("translate")("SOMETHING_WENT_WRONG"));
                self.loading = false;
            })
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserSignCtrl#up
         * @description Send request for register user.
         */
        function up() {
            self.loading = true;
            var data= {
                type: self.type,
                name: self.name,
                surname: self.surname,
                birthDate: self.birthDate.getTime(),
                email: self.email,
                password: self.password,
                password_confirmation: self.passwordConfirm,
                sex: self.sex
            };

            SignService.up(data).then(function (result){
                if (result.status === 'success'){
                    NotificationService.showMessage($filter("translate")(result.message));
                    $rootScope.user = result.data;
                    CookieService.setUser($rootScope.user, "3-m");
                    $state.go("ozelden.user.dashboard");
                } else {
                    NotificationService.showMessage($filter("translate")(result.message));
                }
                self.loading = false;
            }, function () {
                NotificationService.showMessage($filter("translate")("SOMETHING_WENT_WRONG"));
                self.loading = false;
            });
        }

        this.checkValidInput = checkValidInput;
        this._in = _in;
        this.up = up;
    }

    angular.module('ozelden.controllers').controller('UserSignCtrl', UserSignCtrl);
}());