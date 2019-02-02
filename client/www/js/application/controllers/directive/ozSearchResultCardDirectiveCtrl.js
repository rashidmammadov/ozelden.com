(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:ozSearchResultCardDirectiveCtrl
     * @description Controller for the main page view.
     */
    function ozSearchResultCardDirectiveCtrl($scope, $filter) {
        var currentDate = new Date().getTime();

        function dateDifference(given, range, type){
            var result;
            var dateA = new Date(currentDate);
            var dateB = new Date(given);
            if(range === 'year'){
                var year = dateA.getFullYear() - dateB.getFullYear();
                result = year ? year + ' ' + $filter('translate')(type) : '?';
            } else if(range === 'month'){
                result = ((dateA.getFullYear() - dateB.getFullYear())*12 + dateA.getMonth()) - dateB.getMonth();
                var year = Math.floor(result / 12);
                var month = result % 12;
                if (!year && !month) {
                    result = $filter('translate')('NEW');
                } else {
                    result =  (year ? year + ' ' + $filter('translate')('Y') : '') + ' ' +
                        (month ? month + ' ' + $filter('translate')('M') : '');
                }
            }
            return result;
        }

        function setImage(d) {
            var image;
            if (!d.picture) {
                if (d.sex === 'mars') {
                    image = 'background: url("img/default/male-user-white.png") no-repeat center center;';
                } else {
                    image = 'background: url("img/default/female-user-white.png") no-repeat center center;'
                }
            } else {
                image = 'background: url(' + d.picture + ') no-repeat center center;';
            }
            return image + 'width: 80px; height: 80px; border-radius: 100%;'
        }

        this.dateDifference = dateDifference;
        this.setImage = setImage;

    }

    angular.module('ozelden.controllers').controller('ozSearchResultCardDirectiveCtrl', ozSearchResultCardDirectiveCtrl);
}());