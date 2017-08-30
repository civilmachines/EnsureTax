/**
 * Created by Admin on 4/7/2017.
 */
angular.module("101housing").requires.push('homeTemplates', 'LocalStorageModule');
app
    .controller('homeCtrl', ['$scope', '$rootScope', '$state', '$stateParams', '$filter', '$homeModel', '$uibModal', '$appModel', '$window', 'localStorageService', function ($scope, $rootScope, $state, $stateParams, $filter, $homeModel, $uibModal, $appModel, $window, localStorageService) {
        angular.extend($scope, {
            applybusinessItr:function(){
                localStorageService.set('bb', true);
                if ($rootScope.auth) {
                    $window.location.href = "/dashboard/corporate";
                } else {
                    openAuth();
                }
            },
            /*
            welcome: function () {
                $uibModal.open({
                    templateUrl: 'welcome.html',
                    controller: ['$scope', function ($scope) {
                        $scope.close = function () {
                            $scope.$dismiss();
                        };
                        $scope.apply = function () {
                            localStorageService.set('g3b', true);
                            if ($rootScope.auth) {
                                $window.location.href = "/dashboard/gstfiles";
                            } else {
                                $scope.close();
                                openAuth();
                            }
                        }
                    }],
                    windowClass: 'modal-welcome',
                })

                //   change route after modal result
                    .result.then(function () {
                    // change route after clicking OK button
                    /!*  $state.go($state.$current.name);*!/
                }, function () {
                    // change route after clicking Cancel button or clicking background
                    /!* $state.go($state.$current.name);*!/
                })
                // $state.go($state.current.name + '.auth');
            },

*/            applyGST3B: function () {
                localStorageService.set('g3b', true);
                if ($rootScope.auth) {
                    $window.location.href = "/dashboard/gstfiles";
                } else {
                    openAuth();
                }
            },

            applyITR: function (id) {
                localStorageService.set('itr_id', id);
                if ($rootScope.auth) {
                    $window.location.href = "/dashboard";
                } else {
                    openAuth();
                }
            }
        });

        function openAuth() {
            $uibModal.open({
                templateUrl: 'authentication.html',
                controller: 'authCtrl',
                windowClass: 'modal-login'
            })
        }

        // $scope.welcome();
        $scope.$watchCollection(function () {
            return $appModel.loadMasterCategory;
        }, function (newval, oldval) {
            if ($appModel.loadMasterCategory.length >= 0) {
                var loadMaster = $filter('parseParent')($appModel.loadMasterCategory, 0);
                $scope.ITR = loadMaster.itr;
            }
        });

    }]);
   