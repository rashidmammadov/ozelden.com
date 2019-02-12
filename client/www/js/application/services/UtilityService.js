(function () {
    'use strict';

    function UtilityService($filter) {
        var currentDate = new Date().getTime();

        /**
         * @ngdoc method
         * @name ozelden.services.UtilityService#querySearch
         * @methodOf ozelden.services.UtilityService
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
         * @methodOf ozelden.services.UtilityService
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
         * @name ozelden.services.UtilityService#dateDifference
         * @methodOf ozelden.services.UtilityService
         *
         * @description find difference between two milliseconds date.
         * @param {String} given - holds the millisecond date.
         * @param {String} range - holds the range type.
         * @param {String=} type - holds the type.
         */
        this.dateDifference = function(given, range, type) {
            if (given) {
                var result;
                var dateA = new Date(currentDate);
                var dateB = new Date(given);
                if (range === 'year') {
                    var year = dateA.getFullYear() - dateB.getFullYear();
                    result = year ? year + ' ' + $filter('translate')(type) : '?';
                } else if (range === 'month') {
                    result = ((dateA.getFullYear() - dateB.getFullYear()) * 12 + dateA.getMonth()) - dateB.getMonth();
                    var year = Math.floor(result / 12);
                    var month = result % 12;
                    if (!year && !month) {
                        result = $filter('translate')('NEW');
                    } else {
                        result = (year ? year + ' ' + $filter('translate')('Y') : '') + ' ' +
                            (month ? month + ' ' + $filter('translate')('M') : '');
                    }
                }
                return result;
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.services.UtilityService#setMillisecondsDate
         * @methodOf ozelden.services.UtilityService
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