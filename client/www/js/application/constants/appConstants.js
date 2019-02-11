(function() {
    'use strict';

    var days = [];
    for (var i = 1; i<= 31; i++) {
        days.push(i);
    }

    var years = [];
    for (var i = 1900; i<= (new Date()).getFullYear(); i++) {
        years.push(i);
    }

    var constants = {
        days: days,
        months: ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'],
        years: years
    };

    angular.module('ozelden.constants').constant('AppConstants', constants);

}());