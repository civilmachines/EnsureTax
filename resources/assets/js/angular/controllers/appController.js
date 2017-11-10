/**
 * Created by Admin on 4/6/2017.
 */
app
    .controller('appCtrl', ['$scope', '$rootScope', '$state', '$stateParams', '$appModel', function ($scope, $rootScope, $state, $stateParams, $appModel) {

    }])

    .controller('taxTypeCtrl', ['$scope', '$auth', '$authModel','$window', '$uibModal', function ($scope, $auth, $authModel, $window, $uibModal) {
        angular.extend($scope, {
            close: function () {
                $scope.$dismiss();
            },
            incomeTax: function () {
                $scope.$dismiss();
                $uibModal.open({
                    templateUrl: 'login.html',
                    controller: 'authCtrl',
                    windowClass: 'modal-login'
                })
            },
            gstLogin: function () {
                $window.location.href = 'http://gst.ensuretax.com'
                // $window.location.href = 'local.gst.com/';
                /*$uibModal.open({
                 templateUrl: 'gst_login.html',
                 controller: 'gstLoginCtrl',
                 windowClass: 'modal-login',
                 })
                 $scope.$dismiss();*/
            }

        });

    }])

    .controller('authCtrl', ['$scope', '$rootScope', '$state', '$stateParams', '$timeout', '$sce', '$compile', '$auth', '$appModel', '$authModel', 'toastr', function ($scope, $rootScope, $state, $stateParams, $timeout, $sce, $compile, $auth, $appModel, $authModel, toastr) {
        angular.extend($scope, {
            user: {
                email: $stateParams.u
            },
            close: function () {
                $scope.$dismiss();
            },
            alink: function (clsValue) {
                if (clsValue) {
                    $scope.active = 'active ' + clsValue;
                } else {
                    $scope.active = '';
                }
            },
            signup: function () {
                $timeout(function () {
                    $state.go('auth', {q: 'register'});
                });
                $state.go('auth', {q: 'register'});
            },
            signin: function () {
                $timeout(function () {
                    $state.go('auth', {q: 'login'});
                });
                $state.go('auth', {q: 'login'});
            },
            reset: function () {
                $timeout(function () {
                    $state.go('auth', {q: 'reset'});
                });
                $state.go('auth', {q: 'reset'});
            },
            register: function () {
                $scope.$broadcast('show-errors-check-validity');
                if ($scope.registerForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    register($scope.user)
                }
            },
            login: function () {
                $scope.$broadcast('show-errors-check-validity');
                if ($scope.loginForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    login($scope.user);
                }
            },
            resetComplete: function () {
                $scope.$broadcast('show-errors-check-validity');
                if ($scope.resetForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    resetComplete($scope.user);
                }
            },
            otpLogin: function () {
                if ($scope.otpLoginForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    resetRequest($scope.user);
                }
            },
            verify: function () {
                $scope.$broadcast('show-errors-check-validity');
                if ($scope.otpForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    var data = {
                        email: $scope.user.email,
                        otp: $scope.user.otp
                    };
                    verify(data);
                }
            },
            resent: function () {
                if ($scope.user.email) {
                    $appModel.progressBar(true, $scope);
                    resetRequest($scope.user);
                }
            },
            authenticate: function (provider) {
                socialLogin(provider);
            }

        });


        function login(data) {
            $auth.login(data).then(function (result) {
                if (result.data.success) {
                    $authModel.loginSuccess(result.data);
                } else {
                    // $scope.show = true;
                    // $scope.error = 'danger';
                    // if (result.data.hasOwnProperty('activated') && !result.data.activated) {
                    //     $scope.message = $sce.trustAsHtml(result.data.message);
                    //     var html = $compile('<br><span><a href="javascript:void(0);" data-ng-model="user.email" ng-click="resent()" remove-me>Activate Now</a></span>')($scope);
                    //     html.insertAfter(document.getElementById('message'));
                    // } else {
                    //     $scope.message = $sce.trustAsHtml(result.data.message);
                    // }
                    toastr.error(result.data.message, 'Login Denied', {
                        closeButton: true,
                        tapToDismiss: false,
                        extendedTimeOut: 60000,
                        timeOut: 60000,
                    });
                }
                $appModel.progressBar(false, $scope);
            }).catch(function (response) {
                $appModel.progressBar(false, $scope);
            });
        }

        function register(data) {
            $auth.signup(data)
                .then(function (result) {
                    if (result.data.success) {
                        toastr.success(result.data.message);
                        // $scope.alink('otp');
                        $scope.alink('');
                    } else {
                        toastr.warning(result.data.message);
                    }
                    $appModel.progressBar(false, $scope);
                }).catch(function (response) {
                $appModel.progressBar(false, $scope);
            });
        }

        function resetRequest(data) {
            $authModel.resetRequest(data)
                .then(function (result) {
                    //Ensure value that being checked hasn't changed
                    //since the Ajax call was  console.log(data);
                    if (result.data.success) {
                        $scope.alink('otp');
                        toastr.success(result.data.message + ' to ' + data.email);
                    } else {
                        // $scope.confirm = false;
                        // $scope.show = true;
                        // $scope.error = 'danger';
                        // if (result.data.hasOwnProperty('activated') && !result.data.activated) {
                        //     $scope.message = $sce.trustAsHtml(result.data.message);
                        //     var html = $compile('<br><span><a href="javascript:void(0);" data-ng-model="user.email" ng-click="resent()" remove-me>Activate Now</a></span>')(scope);
                        //     html.insertAfter(document.getElementById('message'));
                        // } else {
                        //     $scope.message = $sce.trustAsHtml(result.data.message);
                        // }
                        toastr.error(result.data.message, {
                            closeButton: true,
                            tapToDismiss: false,
                            extendedTimeOut: 60000,
                            timeOut: 60000,
                        });
                    }
                    $appModel.progressBar(false, $scope);
                }).catch(function (response) {
                $appModel.progressBar(false, $scope);
            });
        }

        function resetComplete(data) {
            $authModel.resetComplete(data)
                .then(function (result) {
                    if (result.data.success) {
                        $authModel.loginSuccess(result.data);
                    } else {
                        toastr.warning(result.data.message);
                        // toastr.error(result.data.message, 'Login Denied');
                    }
                    $appModel.progressBar(false, $scope);
                }).catch(function (response) {
                $appModel.progressBar(false, $scope);
            });
        }

        function resend(data) {
            $authModel.reSend(data)
                .then(function (result) {
                    if (result.data.success) {
                        toastr.success(result.data.message);
                        $timeout(function () {
                            $state.go('auth', {q: 'verify', u: $scope.user.email});
                        });
                        $state.go('auth', {q: 'verify', u: $scope.user.email});
                    } else {
                        toastr.warning(result.data.message);
                    }
                    $appModel.progressBar(false, $scope);
                })
                .catch(function (response) {
                    $appModel.progressBar(false, $scope);
                });
        }

        function verify(data) {
            $authModel.verify(data).then(function (result) {
                console.log(result)
                if (result.data.success) {
                    $authModel.loginSuccess(result.data);
                } else {
                    toastr.warning(result.data.message);
                }
                $appModel.progressBar(false, $scope);
            })
                .catch(function (response) {

                });
        }

        function socialLogin(provider) {
            $auth.authenticate(provider)
                .then(function (result) {
                    if (result.data.success) {
                        $authModel.loginSuccess(result.data);
                    }
                })
                .catch(function (response) {
                    toastr.warning('Popup Closed', 'Warning!');
                    // Something went wrong.
                });
        }

    }])

/*

 .controller('gstLoginCtrl', ['$scope', '$appModel', '$state', '$gstModel', function ($scope, $appModel, $state, $gstModel) {
 angular.extend($scope, {
 login: function () {
 if ($scope.loginForm.$valid) {
 $appModel.progressBar(true, $scope);
 var data = {
 username: $scope.user.user_name,
 password: $scope.user.password
 };
 login(data);
 }
 },

 alink: function (clsValue) {
 if (clsValue) {
 $scope.active = 'active ' + clsValue;
 } else {
 $scope.active = '';
 }
 },

 otpLogin: function () {
 if ($scope.otpLoginForm.$valid) {
 $appModel.progressBar(true, $scope);
 var data = {
 value: $scope.user.user_name
 };
 verifyUser(data)
 }
 },
 verifyOtp: function () {
 $scope.$broadcast('show-errors-check-validity');
 if ($scope.otpForm.$valid) {
 $appModel.progressBar(true, $scope);
 var data = {
 value: $scope.user.user_name,
 otp: $scope.user.otp
 };
 console.log(data)
 verifyOTP(data);
 }
 }
 });

 function login(data) {
 $gstModel.gstLogin(data).then(function (result) {
 if (result.data.data.token) {
 $gstModel.gstLoginSuccess(result.data.data);
 } else {
 toastr.error('Invalid Credemtials', 'Error!');
 }
 $appModel.progressBar(false, $scope);
 })
 }

 function verifyUser(data) {
 $scope.alink('otp');
 $appModel.progressBar(false, $scope);
 /!* $gstModel.verifyUser(data).then(function (result) {
 console.log(result)
 })*!/
 }

 function verifyOTP(data) {
 console.log(data)
 $gstModel.verifyUser(data).then(function (result) {
 if (result.data.data.token) {
 $gstModel.gstLoginSuccess(result.data.data);
 } else {
 toastr.error('Invalid Credemtials', 'Error!');
 }
 $appModel.progressBar(false, $scope);
 })
 }
 }])


 */
