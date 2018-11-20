(function () {
    'use strict';

    function UserSuitabilityScheduleCtrl($rootScope, $scope, $filter, CookieService, DataService, UserSettingService, NotificationService){
        var self = this;

        $scope.disableRegionButton = true;
        $scope.saveChangesButton = false;
        $rootScope.loadingOperation = true;

        this.currentUserId = CookieService.getUser().id;
        this.cities = [];
        this.selectedCity;
        this.selectedDistrict;

        this.region;
        this.location;
        this.courseType;
        this.facility;
        this.dayHourTable;

        /**
         * @ngdoc method
         * @description Get default data.
         */
        DataService.get({regions: true}).then(function (result){
            result.regions && (self.cities = result.regions);
            $rootScope.loadingOperation = false;
        },function(rejection){
            $rootScope.loadingOperation = false;
        });

        UserSettingService.getSuitabilitySchedule(self.currentUserId).then(function(result){
            $scope.region = self.region = result.data.region;
            self.location = result.data.location;
            self.courseType = result.data.courseType;
            self.facility = result.data.facility;
            self.dayHourTable = result.data.dayHourTable;
            self.$$setDayHourTableData();
        }, function(rejection){
            NotificationService.showMessage(rejection);
            $rootScope.loadingOperation = false;
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
            }
            if(self.region.length > 4) {
                NotificationService.showMessage($filter('translate')('YOU_CAN_NOT_ADD_MORE_THAN_5_REGIONS'));
            } else {
                var exist = false;
                self.region.forEach(function (object) {
                    if (object.city === region.city && object.district === region.district) {
                        exist = true;
                    }
                });

                !exist ? self.region.push(region) : NotificationService.showMessage($filter('translate')('THIS_REGION_ALREADY_ADDED'));
                $scope.region = self.region;
            }
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:TutorSuitabilityScheduleCtrl#updateDayHourTableData
         * @description Update tutor`s dayHourTable data.
         */
        function updateDayHourTableData(data) {
            angular.forEach(self.dayHourTable, function (value, day) {
                if(day === data.day){
                    value[data.hour] = data.value;
                }
            });
            $scope.saveChangesButton = true;
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:TutorSuitabilityScheduleCtrl#updateSuitabilitySchedule
         * @description Update tutor`s suitability schedule by send request.
         */
        function updateSuitabilitySchedule(){
            $rootScope.loadingOperation = true;
            var data = {
                userId: self.currentUserId,
                region: self.region,
                location: self.location,
                courseType: self.courseType,
                facility: self.facility,
                dayHourTable: self.dayHourTable
            }
            UserSettingService.updateSuitabilitySchedule(data).then(function(result){
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(result.message));
                $scope.saveChangesButton = false;
            },function(rejection){
                $rootScope.loadingOperation = false;
                NotificationService.showMessage($filter('translate')(rejection));
                $scope.saveChangesButton = false;
            });
        }

        /**
         * @ngdoc method
         * @name ozelden.controllers.controllers:TutorSuitabilityScheduleCtrl#$$setDayHourTableData
         * @description Set tutor`s dayHourTable data for directive.
         */
        function $$setDayHourTableData() {
            var data = [];
            angular.forEach(self.dayHourTable, function(value, day){
                angular.forEach(value, function(d, hour){
                    data.push({
                        day: day,
                        hour: hour,
                        value: d
                    });
                });
            });
            $scope.$broadcast('$drawDayHourTable', data);
            $rootScope.loadingOperation = false;
        }

        function activateSaveButton(){
            $scope.saveChangesButton = true;
        }

        $scope.$watch('region.length', function (newVal, oldVal) {
            if (newVal !== undefined && oldVal !== undefined && newVal !== oldVal){
                $scope.saveChangesButton = true;
            }
        });

        this.addRegion = addRegion;
        this.updateSuitabilitySchedule = updateSuitabilitySchedule;
        this.activateSaveButton = activateSaveButton;
        this.updateDayHourTableData = updateDayHourTableData;

        this.$$setDayHourTableData = $$setDayHourTableData;
    }

    angular.module('ozelden.controllers').controller('UserSuitabilityScheduleCtrl', UserSuitabilityScheduleCtrl);
})();