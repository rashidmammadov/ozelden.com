(function () {
    'use strict';

    function UserLecturesListCtrl($rootScope, $scope, $filter, $http, $mdDialog, CookieService, DataService, 
            NotificationService, UserSettingService) {
        var self = this;

        var userId = CookieService.getUser() && CookieService.getUser().id;
        $rootScope.loadingOperation = true;
        this.lectures;
        this.lecturesList = [];

        this.experienceList = [];
        for (var i = 0; i <= 60; i++) { self.experienceList.push(i); }

        /**
         * @ngdoc request
         * @description Get default data.
         */
        DataService.get({lectures: true}).then(function (result) {
            result.lectures && (self.lectures = result.lectures);
            $rootScope.loadingOperation = false;
        }, function() {
            $rootScope.loadingOperation = false;
        });

        /**
         * @ngdoc request
         * @description Get user`s lectures list.
         */
        UserSettingService.getUserLectureList({userId: userId, average: true}).then(function(result) {
            self.lecturesList = result;
            $rootScope.loadingOperation = false;
        }, function() {
            $rootScope.loadingOperation = false;
            NotificationService.showMessage($filter('translate')('SOMETHING_WHEN_WRONG_WHILE_ADDING_LECTURE'));
        });

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserLecturesListCtrl#addLecture
         * @description Add created lecture to lectures list if not exist.
         */
        function addLecture() {
            $rootScope.loadingOperation = true;
            var lectureObject = $$setLectureObject();

            var result = self.lecturesList.find(function(lecture) {
                return $$isEqualLectures(lecture, lectureObject);
            });

            if (!result) {
                UserSettingService.addToUserLectureList(lectureObject).then(function(result) {
                    self.lecturesList.push(lectureObject);
                    $rootScope.loadingOperation = false;
                    NotificationService.showMessage($filter('translate')(result.message));
                },function () {
                    $rootScope.loadingOperation = false;
                    NotificationService.showMessage($filter('translate')('SOMETHING_WHEN_WRONG_WHILE_ADDING_LECTURE'));
                });
            } else {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')('THIS_LECTURE_ALREADY_ADDED'));
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserLecturesListCtrl#removeLecture
         * @description Remove selected lecture from lectures list.
         */
        function removeLecture(event, lecture) {
            $rootScope.loadingOperation = true;
            var confirm = $mdDialog.confirm()
                .title($filter('translate')('REMOVE_LECTURE_TITLE'))
                .textContent($filter('translate')('REMOVE_LECTURE_CONTENT'))
                .ariaLabel('Remove Lecture')
                .targetEvent(event)
                .ok($filter('translate')('CONFIRM'))
                .cancel($filter('translate')('CANCEL'));

            $mdDialog.show(confirm).then(function() {
                $$confirmToRemoveLecture(lecture);
            }, function() {
                $rootScope.loadingOperation = false;
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserLecturesListCtrl#$$confirmToRemoveLecture
         * @description Remove lecture from user`s lectures list.
         * @param {Object} lecture - Holds the selected lecture data.
         */
        function $$confirmToRemoveLecture(lecture) {
            TutorService.removeLectureFromUserLectureList(lecture).then(function(result) {
                self.lecturesList = self.lecturesList.filter(function (value) {
                    return !$$isEqualLectures(value, lecture);
                });
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(result));
            },function (failure) {
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(failure));
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserLecturesListCtrl#$$isEqualLectures
         * @description Check if given lectures is euqal.
         * @param {Object} a - Holds the first given lecture object.
         * @param {Object} b - Holds the second given lecture object.
         */
        function $$isEqualLectures(a, b) {
            return a.lectureArea === b.lectureArea && a.lectureTheme === b.lectureTheme;
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:UserLecturesListCtrl#$$setLectureObject
         * @description Set lecture object with selected values.
         */
        function $$setLectureObject() {
            var lectureObject = {
                lectureArea: self.selectedLectureArea.base,
                lectureTheme: self.selectedLectureTheme.base,
                experience: self.selectedExperience,
                price: self.selectedPrice,
                currency: "TRY",
                average: self.selectedLectureTheme.average.TRY
            };
            return lectureObject;
        }

        this.addLecture = addLecture;
        this.removeLecture = removeLecture;
    }

    angular.module('ozelden.controllers').controller('UserLecturesListCtrl', UserLecturesListCtrl);
})();