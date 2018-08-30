(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:ozSearchResultDirectiveCtrl
     * @description Controller for the main page view.
     */
    function ozSearchResultDirectiveCtrl($scope, SearchService) {
        var self = this;

        this.searchResult;

        function loadMore() {
            var params = {
                offset: $scope.data.length
            };

            SearchService.tutorSearch(params).then(function(result){
                //$scope.data = result;
                $scope.data.push.apply($scope.data, result);
            }, function(failure){

            });
        }

        this.loadMore = loadMore;
    }

    angular.module('ozelden.controllers').controller('ozSearchResultDirectiveCtrl', ozSearchResultDirectiveCtrl);
}());