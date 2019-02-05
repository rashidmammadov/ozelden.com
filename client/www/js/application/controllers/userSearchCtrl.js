(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:UserSearchCtrl
     * @description Controller for the main page view.
     */
    function UserSearchCtrl($rootScope, $scope, $state, $translate, CookieService, SearchService) {
        var self = this;

        $rootScope.loadingOperation = true;
        this.lectures = $rootScope.lectures;
        this.regions = $rootScope.regions;
        this.searchResult = [];

        self.selectedLectureArea = self.lectures[0];
        self.selectedLectureTheme = self.lectures[0].link[0];
        self.selectedCity = 'Izmir';
        self.selectedDistrict = self.regions['Izmir'][0];

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers.UserSearchCtrl#search
         * @description Prepare request to get search result.
         */
        function search() {
            function request(query) {
                $rootScope.loadingOperation = true;
                SearchService.get(query).then(function (d) {
                    $rootScope.loadingOperation = false;
                    self.searchResult = d.data;
                }, function () {
                    $rootScope.loadingOperation = false;
                });
            }
            var query = {};
            query.type = CookieService.getUser().type === 'student' ? 'tutor' : 'student';
            query.lectureArea = self.selectedLectureArea.base;
            query.lectureTheme = self.selectedLectureTheme.base.toLowerCase() === 'all' ? null : self.selectedLectureTheme.base;
            query.city = self.selectedCity;
            query.district = self.selectedDistrict.toLowerCase() === 'hepsi' ? null : self.selectedDistrict;
            request(query);
        }

        search();

        this.search = search;

    }

    angular.module('ozelden.controllers').controller('UserSearchCtrl', UserSearchCtrl);
}());