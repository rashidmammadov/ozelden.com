(function(){
    'use strict';

    function ozClassCard($filter, $timeout, $compile) {

        function link(scope, elem, attrs) {

            var card;

            function drawList(data){
                if (data) {
                    card = elem[0].setAttribute('id', data.classId);
                }
            }

            scope.$watch('data', function(data) {
                $timeout(function() { drawList(data) });
            }, true);
        }

        return {
            restrict: 'E',
            templateUrl: 'html/directive/ozClassCard.html',
            link: link,
            scope: {
                data: '=ngModel',
                edit: '=?onEdit'
            }
        }
    }

    angular.module('ozelden.directives').directive('ozClassCard', ozClassCard);
})();