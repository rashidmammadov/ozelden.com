(function(){
    'use strict';

    function ozSearchResult($filter, $timeout, $compile) {

        function link(scope, elem, attrs){

            function drawList(data){
                if(data) {
                    data.forEach(function (d) {
                        var userCard = angular.element(elem.find('inner-container'));

                        var card = '<oz-search-result-card></oz-search-result-card>';
                        var el = $compile(card)(scope);
                        el[0].__data__ = d;
                        userCard.append(el);
                    });
                }
            }

            scope.$watch('data', function(data) {
                $timeout(function() { drawList(data) });
            }, true);
        }

        return {
            restrict: 'E',
            templateUrl: 'html/directive/ozSearchResult.html',
            controller: 'ozSearchResultDirectiveCtrl',
            controllerAs: 'Board',
            scope: {
                data: '=ngData'
            }
        }
    }

    angular.module('ozelden.directives').directive('ozSearchResult', ozSearchResult);
})();