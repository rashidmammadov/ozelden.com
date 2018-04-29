(function () {
    'use strict';
    
    function TutorService($http, $q, VocabularyService) {
        var self = this;

        /**
         * @ngdoc method
         * @name ozelden.services.TutorService#addTutorLecture
         * @methodOf ozelden.services.TutorService
         *
         * @description add selected lecture to the tutor list.
         * @param {Object} data - holds the tutor`s lecture info.
         */
        function addTutorLecture(data) {
            var deferred = $q.defer();

            $http({
                url: VocabularyService.updateTutorInfo(),
                method: 'POST',
                data: {
                    act: 'addLecture',
                    data: data
                }
            }).then(function(response){
                var result = response.data;
                if (result.success) {
                    deferred.resolve(result.message);
                } else {
                    deferred.reject(result.failure);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.TutorService#getTutorInfo
         * @methodOf ozelden.services.TutorService
         *
         * @description get tutor`s needed info.
         * @param {String} act - the name of act parameter.
         */
        function getTutorInfo(act) {
            var deferred = $q.defer();

            $http({
                url: VocabularyService.getTutorInfo(),
                method: 'GET',
                params: {
                    act: act
                }
            }).then(function (response) {
                var result = response.data;
                if (result.success) {
                    deferred.resolve(result.data);
                } else {
                    deferred.reject(result.failure);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        /**
         * @ngdoc method
         * @name ozelden.services.TutorService#updateTutorInfo
         * @methodOf ozelden.services.TutorService
         *
         * @description update tutor`s given info.
         * @param {String} act - the name of act parameter.
         * @param {Object} data - holds the tutor`s given info.
         */
        function updateTutorInfo(act, data) {
            var deferred = $q.defer();

            $http({
                url: VocabularyService.updateTutorInfo(),
                method: 'POST',
                data: {
                    act: act,
                    data: data
                }
            }).then(function (response) {
                var result = response.data;
                if (result.success) {
                    deferred.resolve(result.message);
                } else {
                    deferred.reject(result.failure);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        this.addTutorLecture = addTutorLecture;
        this.getTutorInfo = getTutorInfo;
        this.updateTutorInfo = updateTutorInfo;
        return this;
    }

    angular.module('ozelden.services').factory('TutorService', TutorService);
})();