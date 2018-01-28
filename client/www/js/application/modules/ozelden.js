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
        }).state('ozelden.tutorDashboard',{
            url: 'tutor/dashboard/{tutorId}',
            templateUrl: 'html/controllers/tutor/dashboard.html',
            controller: 'TutorDashboardCtrl',
            controllerAs: 'dashboard',
            params: {
                tutorId: null
            }
        });

        // define icons.
        $mdIconProvider.icon('briefcase', 'img/icon/briefcase.svg');
        $mdIconProvider.icon('female', 'img/icon/female.svg');
        $mdIconProvider.icon('male', 'img/icon/male.svg');
        $mdIconProvider.icon('lang-az', 'img/icon/lang-az.svg');
        $mdIconProvider.icon('lang-en', 'img/icon/lang-en.svg');
        $mdIconProvider.icon('lang-tr', 'img/icon/lang-tr.svg');
    })
}());