(function () {
    'use strict';

    var ozelden = angular.module('ozelden',['ozelden.controllers','ozelden.directives','ozelden.filters','ozelden.services',
        'ngAnimate', 'ngAria', 'ngMaterial', 'ngMessages', 'pascalprecht.translate', 'ui.router', 'ngCookies']);

    ozelden.run(function ($rootScope, $http, VocabularyService) {});

    ozelden.config(function ($stateProvider, $urlRouterProvider, $translateProvider, $mdIconProvider) {

        // load application language.
        $translateProvider.useStaticFilesLoader({
            prefix: 'data/lang/',
            suffix: '.json'
        });
        $translateProvider.registerAvailableLanguageKeys(['az', 'en', 'tr']);
        $translateProvider.preferredLanguage('tr');
        $translateProvider.useSanitizeValueStrategy('escape');
        
        function getUserData($rootScope, $timeout, SignService, CookieService) {
            var user = CookieService.getUser();
            if (user) {
                return (SignService.myUser(user.remember_token).then(function(result){
                    if (result.status === "success") {
                        CookieService.setUser(result.data, "3-m");
                        return $rootScope.user = result.data;
                    } else {
                        CookieService.removeUser();
                        return $rootScope.user = {};
                    }
                }, function(){
                    CookieService.removeUser();
                    return $rootScope.user = {};
                }))
            }
        }

        // define states for router.
        $urlRouterProvider.otherwise("/");

        $stateProvider.state('ozelden',{
            url: '/',
            templateUrl: 'html/controllers/main.html',
            controller: 'MainCtrl',
            controllerAs: 'Main',
            resolve: {
                user: getUserData
            }
        }).state('ozelden.user',{
            url: 'user',
            templateUrl: 'html/controllers/user/index.html',
            controller: 'UserCtrl',
            controllerAs: 'User'
        }).state('ozelden.user.dashboard',{
            url: '/dashboard',
            templateUrl: 'html/controllers/user/dashboard.html',
            controller: 'UserDashboardCtrl',
            controllerAs: 'Dashboard'
        }).state('ozelden.user.search',{
            url: '/search',
            templateUrl: 'html/controllers/user/search.html',
            controller: 'UserSearchCtrl',
            controllerAs: 'Search'
        }).state('ozelden.user.sign',{
            url: '/sign',
            templateUrl: 'html/controllers/user/sign.html',
            controller: 'UserSignCtrl',
            controllerAs: 'Sign'
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
        $mdIconProvider.icon('birthday-cake', 'img/icon/birthday-cake.svg');
        $mdIconProvider.icon('briefcase', 'img/icon/briefcase.svg');
        $mdIconProvider.icon('dashboard', 'img/icon/dashboard.svg');
        $mdIconProvider.icon('female', 'img/icon/female.svg');
        $mdIconProvider.icon('lang-az', 'img/icon/lang-az.svg');
        $mdIconProvider.icon('lang-en', 'img/icon/lang-en.svg');
        $mdIconProvider.icon('lang-tr', 'img/icon/lang-tr.svg');
        $mdIconProvider.icon('lectures-list', 'img/icon/list.svg');
        $mdIconProvider.icon('location', 'img/icon/location.svg');
        $mdIconProvider.icon('logout', 'img/icon/logout.svg');
        $mdIconProvider.icon('logo', 'img/logo/logo.svg');
        $mdIconProvider.icon('male', 'img/icon/male.svg');
        $mdIconProvider.icon('math-approximately-equal', 'img/icon/math-approximately-equal.svg');
        $mdIconProvider.icon('math-greater-than', 'img/icon/math-greater-than.svg');
        $mdIconProvider.icon('math-infinity', 'img/icon/math-infinity.svg');
        $mdIconProvider.icon('math-less-than', 'img/icon/math-less-than.svg');
        $mdIconProvider.icon('quality', 'img/icon/quality.svg');
        $mdIconProvider.icon('remove-button', 'img/icon/remove-button.svg');
        $mdIconProvider.icon('save', 'img/icon/save.svg');
        $mdIconProvider.icon('side-nav', 'img/icon/side-nav.svg');
        $mdIconProvider.icon('star', 'img/icon/star.svg');
        $mdIconProvider.icon('suitability-schedule', 'img/icon/suitability-schedule.svg');
        $mdIconProvider.icon('total-user', 'img/icon/total-user.svg');
        $mdIconProvider.icon('user-search', 'img/icon/user-search.svg');
    });

})();