(function () {
    'use strict';

    var ozelden = angular.module('ozelden',['ozelden.controllers','ozelden.directives','ozelden.filters','ozelden.services',
        'ngAnimate', 'ngAria', 'ngMaterial', 'ngMessages', 'ui.router']);

    ozelden.config(function ($stateProvider, $urlRouterProvider) {

        $urlRouterProvider.otherwise("/");

        $stateProvider.state('ozelden',{
            url: '/',
            templateUrl: 'html/controllers/main.html',
            controller: 'MainCtrl',
            controllerAs: 'Main'
        })
    })
}());