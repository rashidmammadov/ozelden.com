(function () {
    'use strict';

    var ozelden = angular.module('ozelden',['ozelden.controllers','ozelden.directives','ozelden.filters','ozelden.services',
        'ngAnimate', 'ngAria', 'ngMaterial', 'ngMessages', 'pascalprecht.translate', 'ui.router']);

    ozelden.run(function ($rootScope, $http, VocabularyService) {
        $http({
            method: 'GET',
            url: VocabularyService.getUserSession()
        }).then(function (response) {
            var result = response.data;
            if(result.status === 'success') {
                $rootScope.user = result.user;
            } else {
                $rootScope.user = {};
            }
        }, function(){
            $rootScope.user = {};
        });
    });
    ozelden.config(function ($stateProvider, $urlRouterProvider, $translateProvider, $mdIconProvider) {

        // load application language.
        $translateProvider.useStaticFilesLoader({
            prefix: 'data/lang/',
            suffix: '.json'
        });
        $translateProvider.registerAvailableLanguageKeys(['az', 'en', 'tr']);
        $translateProvider.preferredLanguage('tr');

        // define states for router.
        $urlRouterProvider.otherwise("/");

        $stateProvider.state('ozelden',{
            url: '/',
            templateUrl: 'html/controllers/main.html',
            controller: 'MainCtrl',
            controllerAs: 'Main'
        }).state('ozelden.tutorLogin',{
            url: 'tutor/login',
            templateUrl: 'html/controllers/tutor/login.html',
            controller: 'TutorLoginCtrl',
            controllerAs: 'tutor'
        }).state('ozelden.tutor',{
            url: 'tutor',
            templateUrl: 'html/controllers/tutor/side.html',
            controller: 'TutorSideCtrl',
            controllerAs: 'tutor'
        }).state('ozelden.tutor.dashboard',{
            url: '/dashboard',
            templateUrl: 'html/controllers/tutor/dashboard.html',
            controller: 'TutorDashboardCtrl',
            controllerAs: 'dashboard'
        }).state('ozelden.tutor.lecturesList',{
            url: '/lectures-list',
            templateUrl: 'html/controllers/tutor/lecturesList.html',
            controller: 'TutorLecturesListCtrl',
            controllerAs: 'lectures'
        }).state('ozelden.tutor.suitabilitySchedule',{
            url: '/suitability-schedule',
            templateUrl: 'html/controllers/tutor/suitabilitySchedule.html',
            controller: 'TutorSuitabilityScheduleCtrl',
            controllerAs: 'suitability'
        });

        // define icons.
        $mdIconProvider.icon('add-button', 'img/icon/add-button.svg');
        $mdIconProvider.icon('briefcase', 'img/icon/briefcase.svg');
        $mdIconProvider.icon('dashboard', 'img/icon/dashboard.svg');
        $mdIconProvider.icon('female', 'img/icon/female.svg');
        $mdIconProvider.icon('lang-az', 'img/icon/lang-az.svg');
        $mdIconProvider.icon('lang-en', 'img/icon/lang-en.svg');
        $mdIconProvider.icon('lang-tr', 'img/icon/lang-tr.svg');
        $mdIconProvider.icon('lectures-list', 'img/icon/list.svg');
        $mdIconProvider.icon('male', 'img/icon/male.svg');
        $mdIconProvider.icon('math-approximately-equal', 'img/icon/math-approximately-equal.svg');
        $mdIconProvider.icon('math-greater-than', 'img/icon/math-greater-than.svg');
        $mdIconProvider.icon('math-infinity', 'img/icon/math-infinity.svg');
        $mdIconProvider.icon('math-less-than', 'img/icon/math-less-than.svg');
        $mdIconProvider.icon('save', 'img/icon/save.svg');
        $mdIconProvider.icon('side-nav', 'img/icon/side-nav.svg');
        $mdIconProvider.icon('suitability-schedule', 'img/icon/suitability-schedule.svg');
    })
})();