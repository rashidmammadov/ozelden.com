(function () {
    'use strict';

    function TutorSideCtrl($scope, $state, $stateParams) {
        $scope.height = window.innerHeight;
    }

    angular.module('ozelden.controllers').controller('TutorSideCtrl', TutorSideCtrl);
}());