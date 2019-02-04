(function () {
    'use strict';

    /**
     * @ngdoc controller
     * @name ozelden.controllers.controllers:UserSearchCtrl
     * @description Controller for the main page view.
     */
    function UserSearchCtrl($rootScope, $scope, $state, $translate, CookieService, SearchService) {
        var self = this;

        $rootScope.loadingOperation = false;
        this.lectures = $rootScope.lectures;
        this.regions = $rootScope.regions;
        this.searchResult = [];

        self.selectedLectureArea = self.lectures[0];
        self.selectedLectureTheme = self.lectures[0].link[0];
        self.selectedCity = 'Izmir';
        self.selectedDistrict = self.regions['Izmir'][0];

        /**
         * @ngdoc method
         * @description Get default data.
         */
        function startSampleSearch() {
            var query = {};
            query.type = CookieService.getUser().type === 'student' ? 'tutor' : 'student';
            query.lectureArea = self.selectedLectureArea.base;
            query.selectedLectureTheme = self.selectedLectureTheme.base.toLowerCase() === 'all' ? null : self.selectedLectureTheme.base;
            query.city = self.selectedCity;
            query.district = self.selectedDistrict.toLowerCase() === 'hepsi' ? null : self.selectedDistrict;
        }

        startSampleSearch();

        SearchService.get({type: 'tutor'}).then(function (d) {
            self.searchResult = d.data;
        });

        //this.search = search;

    }

    angular.module('ozelden.controllers').controller('UserSearchCtrl', UserSearchCtrl);
}());