(function () {
    'use strict';

    function TutorLecturesListCtrl($scope, $filter, $http, LocationService, NotificationService, TutorService) {
        var self = this;

        this.loading = true;
        this.lectures;
        this.tutorLecturesList = [];

        this.experienceList = [];
        for(var i=0; i<=50; i++){
            self.experienceList.push(i);
        }

        $http({
            method: 'GET',
            url: LocationService.getLectures()
        }).then(function (response) {
            var result = response.data.lectures;
            self.lectures = result;
        }, function(){
            console.log('did not get lectures');
        });

        TutorService.getTutorInfo('lecturesList').then(function(result){
            self.tutorLecturesList = result;
            self.loading = false;
        }, function(rejection){
            NotificationService.showMessage(rejection);
            self.loading = false;
        });

        function addLecture(){
            self.loading = true;
            var lectureObject = {
                lectureArea: self.selectedLectureArea.base,
                lectureTheme: self.selectedLectureTheme.base,
                experience: self.selectedExperience,
                price: self.selectedPrice,
                currency: "TRY"
            };

            var result = self.tutorLecturesList.find(function(lecture){
                return lecture.lectureArea === lectureObject.lectureArea &&
                        lecture.lectureTheme === lectureObject.lectureTheme;
            });

            if (!result){
                TutorService.addTutorLecture(lectureObject).then(function(result){
                    self.tutorLecturesList.push(lectureObject);
                    self.loading = false;
                    NotificationService.showMessage($filter('translate')(result));
                },function () {
                    self.loading = false;
                    NotificationService.showMessage($filter('translate')('SOMETHING_WHEN_WRONG_WHILE_ADDING_LECTURE'));
                });
            } else {
                self.loading = false;
                NotificationService.showMessage($filter('translate')('THIS_LECTURE_ALREADY_ADDED'));
            }
        }

        this.addLecture = addLecture;
    }

    angular.module('ozelden.controllers').controller('TutorLecturesListCtrl', TutorLecturesListCtrl);
})();