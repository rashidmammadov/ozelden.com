(function(){
    'use strict';

    function ozStudentCard($filter, $timeout, UtilityService) {

        function link(scope, elem, attrs) {

            var card;
            scope.dateDifference = UtilityService.dateDifference;

            function drawList(data) {
                if (data) {
                    card = elem[0].setAttribute('id', data.childId);
                }
            }

            scope.$watch('data', function(data) {
                $timeout(function() { drawList(data) });
            }, true);
        }

        return {
            restrict: 'E',
            templateUrl: 'html/directive/ozStudentCard.html',
            link: link,
            scope: {
                data: '=ngModel',
                operation: '=?onOperation'
            }
        }
    }

    angular.module('ozelden.directives').directive('ozStudentCard', ozStudentCard);
})();