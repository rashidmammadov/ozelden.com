(function(){
    'use strict';

    function ozDateInput(UtilityService, AppConstants) {

        function link(scope) {
            getSelectableDateList();
            setDate();

            scope.onChange = function() {
                scope.data = UtilityService.setMillisecondsDate(scope.date);
            };
            
            function getSelectableDateList() {
                scope.days = AppConstants.days;
                scope.months = AppConstants.months;
                scope.years = AppConstants.years;
            }

            function setDate() {
                scope.date = UtilityService.setObjectDate(scope.millisecond);
                scope.data = UtilityService.setMillisecondsDate(scope.date);
            }
        }

        return {
            restrict: 'E',
            templateUrl: 'html/directive/ozDateInput.html',
            link: link,
            scope: {
                label: '=label',
                data: '=ngModel',
                millisecond: '=?millisecond'
            }
        }
    }

    angular.module('ozelden.directives').directive('ozDateInput', ozDateInput);
})();