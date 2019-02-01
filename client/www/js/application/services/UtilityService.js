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

        return this;
    }

    angular.module('ozelden.services').factory('UtilityService', UtilityService);
}());