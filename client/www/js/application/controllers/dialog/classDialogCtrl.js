(function () {
    'use strict';

    function ClassDialogCtrl($mdDialog) {
        var self = this;

        this.dayMap = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];

        this.day = [
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' },
            {state: false, start: '08:00', end: '12:00' }
        ];

        this.tutors = [
            {id: 1, name: 'Canan Ozbaykal'},
            {id: 2, name: 'Rashid Mammadov'}
        ];

        this.lectures = [
            {lectureArea: 'HIGH_SCHOOL_REINFORCEMENT', lectureTheme: 'TURKISH'},
            {lectureArea: 'HIGH_SCHOOL_REINFORCEMENT', lectureTheme: 'MATH'}
        ];

        this.hours = ['08:00', '09:00', '10:00', '11:00', '12:00'];
        
        function confirm() {
            var dayObject = [];
            self.day.forEach(function (d) { dayObject.push({state: d.state, start: d.start, end: d.end}); });
            var lectureObject = JSON.parse(self.lecture);
            var classData = {
                className: self.className,
                tutorId: Number(self.tutorId),
                lectureArea: lectureObject.lectureArea,
                lectureTheme: lectureObject.lectureTheme,
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