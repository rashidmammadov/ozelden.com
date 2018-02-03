(function () {
    'use strict';

    var ozelden = angular.module('ozelden',['ozelden.controllers','ozelden.directives','ozelden.filters','ozelden.services',
        'ngAnimate', 'ngAria', 'ngMaterial', 'ngMessages', 'pascalprecht.translate', 'ui.router']);

    ozelden.run(function ($rootScope) {
        $rootScope.user = {}
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
        });

        // define icons.
        $mdIconProvider.icon('briefcase', 'img/icon/briefcase.svg');
        $mdIconProvider.icon('dashboard', 'img/icon/dashboard.svg');
        $mdIconProvider.icon('female', 'img/icon/female.svg');
        $mdIconProvider.icon('male', 'img/icon/male.svg');
        $mdIconProvider.icon('lang-az', 'img/icon/lang-az.svg');
        $mdIconProvider.icon('lang-en', 'img/icon/lang-en.svg');
        $mdIconProvider.icon('lang-tr', 'img/icon/lang-tr.svg');
        $mdIconProvider.icon('side-nav', 'img/icon/side-nav.svg');
    })
}());