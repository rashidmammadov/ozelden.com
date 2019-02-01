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
        
        function getStaticData($rootScope, DataService) {
            var params = {lectures: true, regions: true};
            return (DataService.get(params).then(function (result) {
                result.lectures && ($rootScope.lectures = result.lectures);
                result.regions && ($rootScope.regions = result.regions);
            }))
        }
        
        function getUserData($rootScope, $timeout, SignService, CookieService) {
            var user = CookieService.getUser();
            if (user) {
                return (SignService.refreshUser(user.remember_token).then(function(result){
                    if (result.status === "success") {
                        CookieService.setUser(result.data, "14-d");
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
                data: getStaticData,
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
        }).state('ozelden.user.lecturesList',{
            url: '/lectures-list',
            templateUrl: 'html/controllers/user/lecturesList.html',
            controller: 'UserLecturesListCtrl',
            controllerAs: 'Lectures'
        }).state('ozelden.user.suitabilitySchedule',{
            url: '/suitability-schedule',
            templateUrl: 'html/controllers/user/suitabilitySchedule.html',
            controller: 'UserSuitabilityScheduleCtrl',
            controllerAs: 'Suitability'
        }).state('ozelden.user.class',{
            url: '/class',
            templateUrl: 'html/controllers/user/class.html',
            controller: 'UserClassCtrl',
            controllerAs: 'Class'
        }).state('ozelden.user.profile',{
            url: '/profile',
            templateUrl: 'html/controllers/user/profile.html',
            controller: 'UserProfileCtrl',
            controllerAs: 'User'
        });

        // define icons.
        $mdIconProvider.icon('add-button', 'img/icon/add-button.svg');
        $mdIconProvider.icon('birthday-cake', 'img/icon/birthday-cake.svg');
        $mdIconProvider.icon('briefcase', 'img/icon/briefcase.svg');
        $mdIconProvider.icon('class', 'img/icon/class.svg');
        $mdIconProvider.icon('dashboard', 'img/icon/dashboard.svg');
        $mdIconProvider.icon('edit-button', 'img/icon/edit-button.svg');
        $mdIconProvider.icon('false', 'img/icon/false.svg');
        $mdIconProvider.icon('female', 'img/icon/female.svg');
        $mdIconProvider.icon('info', 'img/icon/info.svg');
        $mdIconProvider.icon('lang-az', 'img/icon/lang-az.svg');
        $mdIconProvider.icon('lang-en', 'img/icon/lang-en.svg');
        $mdIconProvider.icon('lang-tr', 'img/icon/lang-tr.svg');
        $mdIconProvider.icon('lectures-list', 'img/icon/list.svg');
        $mdIconProvider.icon('location', 'img/icon/location.svg');
        $mdIconProvider.icon('logout', 'img/icon/logout.svg');
        $mdIconProvider.icon('logo', 'img/logo/logo.svg');
        $mdIconProvider.icon('male', 'img/icon/male.svg');
        $mdIconProvider.icon('math-approximately-equal', 'img/icon/math-approximately-equal.svg');
        $mdIconProvider.icon('math-approximately-equal-filled', 'img/icon/math-approximately-equal-filled.svg');
        $mdIconProvider.icon('math-greater-than', 'img/icon/math-greater-than.svg');
        $mdIconProvider.icon('math-greater-than-filled', 'img/icon/math-greater-than-filled.svg');
        $mdIconProvider.icon('math-infinity', 'img/icon/math-infinity.svg');
        $mdIconProvider.icon('math-infinity-filled', 'img/icon/math-infinity-filled.svg');
        $mdIconProvider.icon('math-less-than', 'img/icon/math-less-than.svg');
        $mdIconProvider.icon('math-less-than-filled', 'img/icon/math-less-than-filled.svg');
        $mdIconProvider.icon('more-details', 'img/icon/more-details.svg');
        $mdIconProvider.icon('profile', 'img/icon/profile.svg');
        $mdIconProvider.icon('quality', 'img/icon/quality.svg');
        $mdIconProvider.icon('remove-button', 'img/icon/remove-button.svg');
        $mdIconProvider.icon('save', 'img/icon/save.svg');
        $mdIconProvider.icon('settings', 'img/icon/settings.svg');
        $mdIconProvider.icon('side-nav', 'img/icon/side-nav.svg');
        $mdIconProvider.icon('star', 'img/icon/star.svg');
        $mdIconProvider.icon('suitability-schedule', 'img/icon/suitability-schedule.svg');
        $mdIconProvider.icon('total-user', 'img/icon/total-user.svg');
        $mdIconProvider.icon('true', 'img/icon/true.svg');
        $mdIconProvider.icon('upload', 'img/icon/upload.svg');
        $mdIconProvider.icon('user-search', 'img/icon/user-search.svg');
    });

})();