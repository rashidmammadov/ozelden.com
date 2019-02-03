(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:ozSearchResultDirectiveCtrl
     * @description Controller for the main page view.
     */
    function ozSearchResultDirectiveCtrl($scope, $timeout, SearchService) {

        this.searchResult;

        function loadMore() {
            var params = {
                offset: $scope.data.length
            };

            SearchService.tutorSearch(params).then(function(result){
                $scope.data.push.apply($scope.data, result);

            }, function(failure) {});
        }

        var DynamicItems = this.DynamicItems = function(data) {
            this.loadedPages = {};
            this.numItems = 0;
            this.PAGE_SIZE = 10;
            this.fetchNumItems_();
            this.data = data;
        };

        DynamicItems.prototype.getItemAtIndex = function(index) {
            var pageNumber = Math.floor(index / this.PAGE_SIZE);
            var page = this.loadedPages[pageNumber];

            if (page) {
                return page[index % this.PAGE_SIZE];
            } else if (page !== null) {
                this.fetchPage_(pageNumber);
            }
        };

        DynamicItems.prototype.getLength = function() {
            return this.numItems;
        };

        DynamicItems.prototype.fetchPage_ = function(pageNumber) {
            this.loadedPages[pageNumber] = null;

            $timeout(angular.noop, 300).then(angular.bind(this, function() {
                this.loadedPages[pageNumber] = [];
                var pageOffset = pageNumber * this.PAGE_SIZE;
                for (var i = pageOffset; i < pageOffset + this.PAGE_SIZE; i++) {
                    this.data[i] && this.loadedPages[pageNumber].push(this.data[i]);
                }
            }));
        };

        DynamicItems.prototype.fetchNumItems_ = function() {
            $timeout(angular.noop, 300).then(angular.bind(this, function() {
                this.numItems = this.data.length;
            }));
        };

        this.dynamicItems = new DynamicItems($scope.data);
        this.loadMore = loadMore;
    }

    angular.module('ozelden.controllers').controller('ozSearchResultDirectiveCtrl', ozSearchResultDirectiveCtrl);
}());