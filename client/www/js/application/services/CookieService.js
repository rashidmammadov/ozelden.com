(function () {
    'use strict';

    function CookieService($cookies) {

        function getUser() {
            return $cookies.getObject('user');
        }

        function setUser(data, time) {
            $cookies.putObject('user', data, {expires: $$setExpireDate(time)});
        }

        function removeUser() {
            $cookies.remove('user');
        }

        function $$setExpireDate(time) {
            var currentDate = new Date();
            var value = time.split("-");
            var key = Number(value[0]);
            var expireDate;
            if (value[1].toLowerCase() === 'y') {
                expireDate = new Date((currentDate.getFullYear() + key), currentDate.getMonth(), currentDate.getDate());
            } else if (value[1].toLowerCase() === 'm') {
                expireDate = new Date(currentDate.getFullYear(), (currentDate.getMonth() + key), currentDate.getDate());
            } else if (value[1].toLowerCase() === 'd') {
                expireDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), (currentDate.getDate() + key));
            }
            return expireDate;
        }

        this.getUser = getUser;
        this.setUser = setUser;
        this.removeUser = removeUser;

        return this;
    }

    angular.module('ozelden.services').factory('CookieService', CookieService);
}());