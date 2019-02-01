(function () {
    'use strict';

    function ClassDialogCtrl($scope, $mdDialog, $state, locals, CookieService, UtilityService) {
        var self = this;
        var userId = CookieService.getUser() && CookieService.getUser().id;

        $scope.operation = locals.type;
        $scope.data = locals.data;
        var classId = $scope.data ? $scope.data.classId : null;
        this.tutors = locals.tutors;
        this.lectures = locals.lectures;
        this.regions = locals.regions;
        this.dayHourTable = locals.dayHourTable;

        this.dayMap = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];
        this.hours = [];
        for (var i = 8; i < 24; i++) {
            for (var j = 0; j < 60; j += 5) {
                var h = i < 10 ? '0'.concat(i) : i;
                var m = j < 10 ? '0'.concat(j) : j;
                self.hours.push([h, m].join(':'));
            }
        }

        var defaultDay = [];
        for (var i = 0; i < 7; i++) { defaultDay.push({state: false, start: '08:00', end: '12:00' }); }

        this.className = $scope.data ? $scope.data.className : null;
        this.tutor = $scope.data ? $scope.data.tutor.tutorId : userId;
        this.lecture = $scope.data ? {lectureArea: $scope.data.lectureArea, lectureTheme: $scope.data.lectureTheme} : null;
        this.region = $scope.data ? {city: $scope.data.city, district: $scope.data.district} : null;
        this.content = $scope.data ? $scope.data.content : {};
        this.day = ($scope.data && $scope.data.day) ? $scope.data.day : defaultDay;

        this.querySearch = UtilityService.querySearch;
        
        function confirm() {
            var dayObject = [];
            self.day.forEach(function (d) { dayObject.push({state: d.state, start: d.start, end: d.end}); });
            var lectureObject = JSON.parse(self.lecture);
            var regionObject = JSON.parse(self.region);
            var classData = {
                classId: classId,
                className: self.className,
                tutorId: Number(self.tutor),
                lectureArea: lectureObject.lectureArea,
                lectureTheme: lectureObject.lectureTheme,
                city: regionObject.city,
                district: regionObject.district,
                content: self.content,
                day: dayObject
            };
            $mdDialog.hide(classData);
        }

        this.cancel = $mdDialog.cancel;
        this.confirm = confirm;
    }

    angular.module('ozelden.controllers').controller('ClassDialogCtrl', ClassDialogCtrl);
}());