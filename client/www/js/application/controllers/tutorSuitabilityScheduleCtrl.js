(function () {
    'use strict';

    function TutorSuitabilityScheduleCtrl($scope, $http, $filter, TutorService, LocationService, NotificationService){
        var self = this;

        $scope.disableRegionButton = true;
        $scope.saveChangesButton = false;
        this.loading = true;

        this.cities = [];
        this.selectedCity;
        this.selectedDistrict;

        this.region;
        this.location;
        this.courseType;
        this.facility;
        this.dayHourTable;

        $http({
            method: 'GET',
            url: LocationService.getCities()
        }).then(function (response) {
            var result = response.data.cities;
            self.cities = result;
        }, function(){
            console.log('did not get cities');
        });

        TutorService.getTutorInfo('suitabilitySchedule').then(function(result){
            $scope.region = self.region = result.region;
            self.location = result.location;
            self.courseType = result.courseType;
            self.facility = result.facility;
            self.dayHourTable = result.dayHourTable;
            self.$$setDayHourTableData();
        }, function(rejection){
            NotificationService.showMessage(rejection);
            self.loading = false;
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
            self.loading = true;
            var data = {
                region: self.region,
                location: self.location,
                courseType: self.courseType,
                facility: self.facility,
                dayHourTable: self.dayHourTable
            }
            TutorService.updateTutorInfo('suitabilitySchedule', data).then(function(result){
                self.loading = false;
                NotificationService.showMessage($filter('translate')(result));
                $scope.saveChangesButton = false;
            },function(rejection){
                self.loading = false;
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
            self.loading = false;
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

    angular.module('ozelden.controllers').controller('TutorSuitabilityScheduleCtrl', TutorSuitabilityScheduleCtrl);
})();