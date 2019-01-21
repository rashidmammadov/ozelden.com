(function () {
    'use strict';

    function ClassDialogCtrl($scope, $mdDialog, $state, locals, CookieService) {
        var self = this;
        var userId = CookieService.getUser() && CookieService.getUser().id;

        $scope.operation = locals.type;
        $scope.data = locals.data;
        var classId = $scope.data ? $scope.data.classId : null;
        this.tutors = locals.tutors;
        this.lectures = locals.lectures;
        this.regions = locals.regions;

        this.dayMap = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];
        this.hours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00',
            '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', "23:00", "24:00"];

        var defaultDay = [
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' }
        ];

        this.className = $scope.data ? $scope.data.className : null;
        this.tutor = $scope.data ? $scope.data.tutor.tutorId : userId;
        this.lecture = $scope.data ? {lectureArea: $scope.data.lectureArea, lectureTheme: $scope.data.lectureTheme} : null;
        this.region = $scope.data ? {city: $scope.data.city, district: $scope.data.district} : null;
        this.content = $scope.data ? $scope.data.content : {};
        this.day = ($scope.data && $scope.data.day) ? $scope.data.day : defaultDay;
        
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