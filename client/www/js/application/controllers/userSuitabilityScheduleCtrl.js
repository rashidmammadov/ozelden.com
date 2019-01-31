(function () {
    'use strict';

    function UserSuitabilityScheduleCtrl($rootScope, $scope, $filter, UserSettingService, NotificationService){
        var self = this;

        $scope.saveChangesButton = false;
        $rootScope.loadingOperation = true;

        this.cities = $rootScope.regions;
        this.selectedCity = null;
        this.selectedDistrict = null;

        /**
         * @ngdoc param
         * @description Get user`s suitability schedule.
         */
        UserSettingService.getSuitabilitySchedule().then(function(result) {
            $rootScope.loadingOperation = false;
            self.region = result.data.region;
            self.location = result.data.location;
            self.courseType = result.data.courseType;
            self.facility = result.data.facility;
            self.dayHourTable = result.data.dayHourTable;
            self.$$setDayHourTableData();
        }, function(rejection){
            $rootScope.loadingOperation = false;
            NotificationService.showMessage(rejection);
        });

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:TutorSuitabilityScheduleCtrl#addRegion
         * @description Add selected city and district to region array if not exist.
         */
        function addRegion() {
            var region = {
                city: self.selectedCity,
                district: self.selectedDistrict
            };
            if (self.region.length > 4) {
                NotificationService.showMessage($filter('translate')('YOU_CAN_NOT_ADD_MORE_THAN_5_REGIONS'));
            } else {
                var exist = false;
                self.region.forEach(function (object) {
                    if (object.city === region.city && object.district === region.district) {
                        exist = true;
                    }
                });

                if (!exist) {
                    self.region.push(region);
                    updateSuitabilitySchedule({'region': self.region});
                } else {
                    NotificationService.showMessage($filter('translate')('THIS_REGION_ALREADY_ADDED'));
                }
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:TutorSuitabilityScheduleCtrl#updateDayHourTableData
         * @description Update tutor`s dayHourTable data.
         */
        function updateDayHourTableData(data) {
            angular.forEach(self.dayHourTable, function (value, day) {
                if (day === data.day) {
                    value[data.hour] = data.value;
                }
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:TutorSuitabilityScheduleCtrl#updateSuitabilitySchedule
         * @description Update tutor`s suitability schedule by send request.
         * @param {Object} data - hold the changed object.
         */
        function updateSuitabilitySchedule(data) {
            $rootScope.loadingOperation = true;
            UserSettingService.updateSuitabilitySchedule(data).then(function(result){
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(result.message));
            },function(rejection){
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(rejection));
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:TutorSuitabilityScheduleCtrl#$$setDayHourTableData
         * @description Set tutor`s dayHourTable data for directive.
         */
        function $$setDayHourTableData() {
            $rootScope.loadingOperation = false;
            var data = [];
            angular.forEach(self.dayHourTable, function(value, day) {
                angular.forEach(value, function(d, hour) {
                    data.push({
                        day: day,
                        hour: hour,
                        value: d
                    });
                });
            });
            $scope.$broadcast('$drawDayHourTable', data);
        }

        this.addRegion = addRegion;
        this.updateSuitabilitySchedule = updateSuitabilitySchedule;
        this.updateDayHourTableData = updateDayHourTableData;

        this.$$setDayHourTableData = $$setDayHourTableData;
    }

    angular.module('ozelden.controllers').controller('UserSuitabilityScheduleCtrl', UserSuitabilityScheduleCtrl);
})();