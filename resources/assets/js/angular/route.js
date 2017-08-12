/**
 * Created by Admin on 4/6/2017.
 */
app
    .config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
        function($stateProvider, $urlRouterProvider, $locationProvider) {
            var $modalInstance;
            // States
            $stateProvider
                .state('app', {
                    abstract: true,
                    url: '/',
                    template: '<hs-header></hs-header><div id="view2" ui-view=""></div><hs-footer></hs-footer>',
                    // resolve: {
                    //     resolveData: ['$homeModel', function ($homeModel) {
                    //         return $homeModel.getCategory();
                    //     }]
                    // }
                })
                .state('about', {
                    url: '/about',
                })
                .state('career', {
                    url: '/career',
                })
                .state('services', {
                    url: '/services',
                })
                .state('contact', {
                    url: '/contact',
                    views: {
                        'contactUs': {
                            templateUrl: 'contact.html',
                            controller: 'contactController'
                        },
                    }

                })
                .state('terms', {
                    url: '/terms',
                })
                .state('faq', {
                    url: '/faq',
                })
                .state('home', {
                    parent: 'app',
                    url: '',
                    templateUrl: 'home.html',
                    controller: 'homeCtrl',
                    data: {
                        bodyClass: '',
                        navClass: ''
                    },
                })
                .state('dash', {
                    parent: 'app',
                    url: 'dashboard',
                    abstract: true,
                    templateUrl: 'dashboard-link.html',
                    data: {
                        bodyClass: 'fixed-sn',
                        navClass: ''
                    },
                    authenticate: true
                })

                .state('itr', {
                    parent: 'dash',
                    url: '',
                    templateUrl: 'itr_list.html',
                    resolve: {
                        'resolveData': ['$rootScope', '$stateParams', '$dashboardModel', function($rootScope, $stateParams, $dashboardModel) {
                            return $dashboardModel.ITRList($stateParams);
                        }]
                    },
                    authenticate: true,
                    controller: 'ITRListCtrl'

                })

                .state('corporate-itr', {
                    parent: 'dash',
                    url: '/corporate',
                    templateUrl: 'corporate_itr_list.html',
                    resolve: {
                        'resolveData': ['$rootScope', '$stateParams', '$dashboardModel', function($rootScope, $stateParams, $dashboardModel) {
                            return $dashboardModel.CorporateITRList($stateParams);
                        }]
                    },
                    authenticate: true,
                    controller: 'corporateITRList'

                })

                .state('add-corp-itr', {
                    parent: 'dash',
                    url: '/corporate/add/:id',
                    templateUrl: 'corporate_itr.html',
                    resolve: {
                        'resolveData': ['$stateParams', '$dashboardModel', function($stateParams, $dashboardModel) {
                            if ($stateParams.hasOwnProperty('id') && $stateParams.id > 0)
                                return $dashboardModel.editCorpITR($stateParams.id);
                        }]
                    },
                    controller: 'addCorporateITR',
                    authenticate: true
                })

                .state('gst-files', {
                    parent: 'dash',
                    url: '/gstfiles',
                    templateUrl: 'gst_files.html',
                    resolve: {
                        'resolveData': ['$rootScope', '$stateParams', '$dashboardModel', function($rootScope, $stateParams, $dashboardModel) {
                            return $dashboardModel.gstFiles($stateParams);
                        }]
                    },
                    authenticate: true,
                    controller: 'gstFilesCtrl'
                })

                .state('upload-gst', {
                    parent: 'dash',
                    url: '/gstfiles/upload/:id',
                    templateUrl: 'gst_upload.html',
                    resolve: {
                        'resolveData': ['$stateParams', '$dashboardModel', function($stateParams, $dashboardModel) {
                            if ($stateParams.hasOwnProperty('id') && $stateParams.id > 0)
                                return $dashboardModel.editGST($stateParams.id);
                        }]
                    },
                    controller: 'uploadGSTCtrl',
                    authenticate: true
                })


                /* .state('dashboard', {
                 parent: 'dash',
                 url: '',
                 templateUrl: 'itr_list.html',
                 authenticate: true
                 })*/
                .state('profile', {
                    parent: 'dash',
                    url: '/profile',
                    templateUrl: 'my-profile.html',
                    controller: 'profileCtrl',
                    authenticate: true
                })
                .state('password', {
                    parent: 'dash',
                    url: '/password',
                    templateUrl: 'change-password.html',
                    controller: 'passwordCtrl',
                    authenticate: true
                })

                .state('add-itr', {
                    parent: 'dash',
                    url: '/itr/add/:id',
                    templateUrl: 'itr_add.html',
                    resolve: {
                        'resolveData': ['$stateParams', '$dashboardModel', function($stateParams, $dashboardModel) {
                            if ($stateParams.hasOwnProperty('id') && $stateParams.id > 0)
                                return $dashboardModel.editITR($stateParams.id);
                        }]
                    },
                    controller: 'addITRCtrl',
                    authenticate: true
                });


            $locationProvider.html5Mode(true);
            $urlRouterProvider.otherwise('/');
        }
    ])

