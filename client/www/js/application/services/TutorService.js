(function () {
    'use strict';
    
    function TutorService($http, $q, VocabularyService) {
        var self = this;

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
            }).then(function (result) {
                var response = result.data;
                if (response.success) {
                    deferred.resolve(response.data);
                } else {
                    deferred.reject(response.failure);
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
            }).then(function (result) {
                var response = result.data;
                if (response.success) {
                    deferred.resolve(response.message);
                } else {
                    deferred.reject(response.failure);
                }
            }, function (rejection) {
                deferred.reject(rejection);
            });

            return deferred.promise;
        }

        this.getTutorInfo = getTutorInfo;
        this.updateTutorInfo = updateTutorInfo;
        return this;
    }

    angular.module('ozelden.services').factory('TutorService', TutorService);
})();