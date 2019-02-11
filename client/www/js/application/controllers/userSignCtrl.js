(function () {
    'use strict';

    function UserSignCtrl($rootScope, $scope, $state, $http, $filter, CookieService, NotificationService, SignService) {
        var self = this;

        if (CookieService.getUser()) {
            $state.go('ozelden.user.dashboard');
        }

        $scope.tabindex = 0;
        $rootScope.loadingOperation = false;

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
         * @name ozelden.controllers.controllers:UserSignCtrl#_in
         * @description Send request for log in user.
         */
        function _in() {
            $rootScope.loadingOperation = true;
            var data = {
                email: self.signInEmail,
                password: self.signInPassword
            };

            SignService._in(data).then(function (result) {
                if (result.status === 'success') {
                    NotificationService.showMessage($filter('translate')(result.message));
                    $rootScope.user = result.data;
                    CookieService.setUser($rootScope.user, '14-d');
                    $state.go('ozelden.user.dashboard');
                } else {
                    NotificationService.showMessage($filter('translate')(result.message));
                }
                $rootScope.loadingOperation = false;
            }, function (rejection) {
                NotificationService.showMessage($filter('translate')(rejection.message));
                $rootScope.loadingOperation = false;
            })
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserSignCtrl#up
         * @description Send request for register user.
         */
        function up() {
            if ($scope.SignUpForm.$valid && self.password === self.passwordConfirm && self.terms === true) {
                $rootScope.loadingOperation = true;
                var data = {
                    type: self.type,
                    name: self.name,
                    surname: self.surname,
                    birthDate: self.birthDate.getTime(),
                    email: self.email,
                    password: self.password,
                    password_confirmation: self.passwordConfirm,
                    sex: self.sex
                };

                SignService.up(data).then(function (result) {
                    if (result.status === 'success') {
                        NotificationService.showMessage($filter('translate')(result.message));
                        $rootScope.user = result.data;
                        CookieService.setUser($rootScope.user, '14-d');
                        $state.go('ozelden.user.dashboard');
                    } else {
                        NotificationService.showMessage($filter('translate')(result.message));
                    }
                    $rootScope.loadingOperation = false;
                }, function (rejection) {
                    NotificationService.showMessage($filter('translate')(rejection.message));
                    $rootScope.loadingOperation = false;
                });
            } else {
                NotificationService.showMessage($filter('translate')('FIELDS_VALIDATION_FAILED'));
            }
        }

        this._in = _in;
        this.up = up;
    }

    angular.module('ozelden.controllers').controller('UserSignCtrl', UserSignCtrl);
}());