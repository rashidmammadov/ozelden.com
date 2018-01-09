(function () {
    'use strict';

    var ozelden = angular.module('ozelden',['ozelden.controllers','ozelden.directives','ozelden.filters','ozelden.services',
        'ngAnimate', 'ngAria', 'ngMaterial', 'ngMessages', 'pascalprecht.translate', 'ui.router']);

    ozelden.config(function ($stateProvider, $urlRouterProvider, $translateProvider) {

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
        })
    })
}());