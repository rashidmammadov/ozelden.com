(function(){
    'use strict';

    function ozDateInput(UtilityService, AppConstants) {

        function link(scope) {
            scope.days = AppConstants.days;
            scope.months = AppConstants.months;
            scope.years = AppConstants.years;
            scope.onChange = function() {
                scope.data = UtilityService.setMillisecondsDate(scope.date);
            };
        }

        return {
            restrict: 'E',
            templateUrl: 'html/directive/ozDateInput.html',
            link: link,
            scope: {
                label: '=label',
                data: '=ngModel'
            }
        }
    }

    angular.module('ozelden.directives').directive('ozDateInput', ozDateInput);
})();