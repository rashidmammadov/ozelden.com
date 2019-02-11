(function () {
    'use strict';

    function UtilityService() {

        /**
         * @ngdoc method
         * @name ozelden.services.UtilityService#querySearch
         * @methodOf ozelden.services.TutorService
         *
         * @description get current user`s info by token.
         * @param {String} query - holds the user token.
         */
        this.querySearch = function(query, tags, additionalTags) {
            additionalTags || (additionalTags = []);
            function createFilterFor(query) {
                return function(tag) {
                    var key = tag.label ? tag.label : tag;
                    return key.toLowerCase().indexOf(query.toLowerCase()) !== -1;
                };
            }
            return query ? tags.concat(additionalTags).filter(createFilterFor(query)) : tags;
        };

        /**
         * @ngdoc method
         * @name ozelden.services.UtilityService#findLectureAverage
         * @methodOf ozelden.services.TutorService
         *
         * @description find lecture average.
         * @param {Object} lecture - holds the lecture data.
         */
        this.findLectureAverage = function(lecture) {
            var diff = { fill: '#707070', icon: 'math-infinity-filled', status: 'UNDEFINED' };
            if (lecture.average > 0) {
                if ((lecture.average - lecture.experience * 5) <= lecture.price && (lecture.average - (-lecture.experience * 5)) >= lecture.price) {
                    diff = { fill: 'darkgreen', icon: 'math-approximately-equal-filled', status: 'AVAILABLE' }
                } else if ((lecture.average - lecture.experience * 5) > lecture.price) {
                    diff = { fill: '#D1A377', icon: 'math-less-than-filled', status: 'CHEAP' }
                } else if ((lecture.average + lecture.experience * 5) < lecture.price) {
                    diff = { fill: 'darkred', icon: 'math-greater-than-filled', status: 'EXPENSIVE' }
                }
            }
            return diff;
        };

        /**
         * @ngdoc method
         * @name ozelden.services.UtilityService#setMillisecondsDate
         * @methodOf ozelden.services.TutorService
         *
         * @description calculate milliseconds date.
         * @param {Object=} value - holds the date values.
         */
        this.setMillisecondsDate = function (value) {
            var date = null;
            if (value.day && value.month && value.year) {
                date = new Date(value.year, value.month, value.day);
            } else {
                date = new Date();
            }
            return date.getTime();
        };

        return this;
    }

    angular.module('ozelden.services').factory('UtilityService', UtilityService);
}());