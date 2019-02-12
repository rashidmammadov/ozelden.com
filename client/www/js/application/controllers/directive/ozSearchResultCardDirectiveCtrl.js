(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:ozSearchResultCardDirectiveCtrl
     * @description Controller for the main page view.
     */
    function ozSearchResultCardDirectiveCtrl($scope, $filter, UtilityService) {

        this.dateDifference = UtilityService.dateDifference;
        this.findLectureAverage = UtilityService.findLectureAverage;

        function setImage(d) {
            if (d) {
                var image;
                if (!d.picture) {
                    if (d.sex === 'mars') {
                        image = 'background: url("img/default/male-user-white.png") no-repeat center center;';
                    } else {
                        image = 'background: url("img/default/female-user-white.png") no-repeat center center;'
                    }
                } else {
                    image = 'background: url(' + $$setProfilePicture(d.picture) + ') no-repeat center center;';
                }
                return image + 'width: 80px; height: 80px; border-radius: 100%; background-size: cover;'
            }
        }

        function $$setProfilePicture(originalUrl) {
            return originalUrl ? originalUrl : null;
        }

        this.setImage = setImage;

    }

    angular.module('ozelden.controllers').controller('ozSearchResultCardDirectiveCtrl', ozSearchResultCardDirectiveCtrl);
}());