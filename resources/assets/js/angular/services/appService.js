/**
 * Created by Admin on 4/6/2017.
 */
app
    .factory('$appModel', ['$http', '$interval', '$API', '$state', '$auth', 'toastr', '$window', 'localStorageService', function ($http, $interval, $API, $state, $auth, toastr, $window, localStorageService) {
        var $appModel = {};
        loadAllCategory();
        // var categoryData = {
        //     type1: 'Residential',
        //     type2: 'commercial',
        //     type3: 'rooms',
        //     type4: 'furnished',
        //     type5: 'prop-avail-from',
        // }
        $appModel.loadMasterCategory = {};
        $appModel.loadCity = {};
        $appModel.loadMaster = {};
        $appModel.loadCities = function () {
            return $http({
                headers: {
                    'Content-Type': 'application/json,Cache-Control:public, max-age=31536000'
                },
                url: $API.API_GET_CITY,
                method: 'GET',
            }).success(function (response) {
                if (response.success) {
                    $appModel.loadCity = response.city.city;
                }
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                // return data;
            });
        };
        $appModel.error = function (data, status) {
            switch (status) {
                case 422:
                    angular.forEach(data.error, function (value, key) {
                        toastr.error(key + ': ' + value, 'Error');
                    });
                    break;
                case 401:
                case 405:
                case 403:
                case 400:
                    toastr.error(data.message);
                    $appModel.logout();
                    break;
                default:
                    toastr.error(data.message);
                    break;
            }
        };
        $appModel.logout = function () {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_USER_LOGOUT,
                method: 'POST',
                data: {token: $auth.getToken()},
            }).success(function (response) {
                if (response.success) {
                    $auth.logout();
                    localStorageService.clearAll()
                    // $state.reload();
                    $window.location.href = '/';
                }
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                $window.location.href = '/';
            });

        };
        $appModel.contactUS = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.POST_CONTACT_US,
                method: 'POST',
                data: data,
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return data;
            });
        };

        $appModel.progressBar = function (status, scope) {
            var j = 0, counter = 0;
            scope.submit = status;
            scope.mode = 'buffer';
            scope.activated = status;
            scope.determinateValue = 30;
            scope.determinateValue2 = 30;
            scope.modes = [];
            $interval(function () {
                if (scope.activated) {
                    scope.determinateValue += 1;
                    scope.determinateValue2 += 1.5;
                    if (scope.determinateValue > 100)
                        scope.determinateValue = 30;
                    if (scope.determinateValue2 > 100)
                        scope.determinateValue2 = 30
                    // Incrementally start animation the five (5) Indeterminate,
                    // themed progress circular bars
                    if ((j < 2) && !scope.modes[j]) {
                        scope.modes[j] = (j == 0) ? 'buffer' : 'query';
                    }
                }
            }, 100, 0, true);
        };
        function loadAllCategory() {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_AUTH_GET_ALL_CATEGORY,
                method: 'GET'
            }).success(function (response) {
                if (response.success) {
                    $appModel.loadMasterCategory = response.data;
                }
                return response;
            }).error(function (data, status, headers) {
                return console.log(data);
            });
        }

        return $appModel;

        function getCatgeory() {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_GET_CATEGORY,
                cache: true,
                method: 'GET',
            }).success(function (response) {
                if (response.success) {
                    $appModel.loadMaster = response.category;
                }
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                // return data;
            });
        }
    }])
    .factory('$authModel', ['$http', '$API', '$CONFIG', '$state', '$auth', '$window', 'toastr', '$appModel', 'localStorageService', function ($http, $API, $CONFIG, $state, $auth, $window, toastr, $appModel, localStorageService) {
        var $authModel = {};
        var $authUser = {};
        $authModel.checkExist = function (property, value) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_CHECK_UNIQUE,
                method: 'POST',
                data: {
                    property: property,
                    value: value
                }
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                // return data;
            });
        };
        $authModel.verify = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_VERIFY,
                method: 'POST',
                data: data,
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                return data;
            });
        };
        $authModel.checkOTP = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_CHECK_OTP,
                method: 'POST',
                data: data,
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                // return data;
            });
        };
        $authModel.resetRequest = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_RESET_REQUEST,
                method: 'POST',
                data: data,
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                // return data;
            });
        };
        $authModel.resetComplete = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_RESET_COMPLETE,
                method: 'POST',
                data: data,
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                // return data;
            });
        };
        $authModel.reSend = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_RESEND,
                method: 'POST',
                data: data,
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                return data;
            });
        };
        $authModel.loginSuccess = function (data) {
            $auth.setToken(data.data.token);
            // $timeout(function () {
            //     $state.go('dashboard');
            // })
            // $state.go('dashboard');
            toastr.success('Welcome to ensureTax.', 'Successful!');
            if (localStorageService.get('itr_id'))
                $window.location.href = '/dashboard';
            else if (localStorageService.get('g3b'))
                $window.location.href = '/dashboard/gstfiles';
            else if (localStorageService.get('bb'))
                $window.location.href = '/dashboard/corporate';
            else
                $window.location.href = '/dashboard';
        };

        $authModel.hasPermission = function ($role, $requireAll) {
            if (angular.isArray($role)) {
                var results = $role.map(this.hasPermission);
                return $requireAll ? results.every(function (item) {
                    return item;
                }) : results.some(function (item) {
                    return item;
                })
            } else {
                var data = $authModel.getUser();
                if ($role == $CONFIG.$ROLES.ADMINISTRATOR)
                    return (data.hasOwnProperty($role) && data[$role]);
                else
                    return (data.hasOwnProperty($CONFIG.$ROLES.REGISTERED) && data.hasOwnProperty($role) && data[$CONFIG.$ROLES.REGISTERED] && data[$role]);
            }
        };
        $authModel.viewPermission = function ($role) {
            var data = $authModel.getUser();
            return (data.hasOwnProperty($role) && data[$role]);
        };
        $authModel.getUser = function () {
            return $authUser;
        };
        $authModel.setUser = function (data) {
            $authUser = {
                name: data.nam,
                email: data.em,
                contact: data.con,
                image: data.img
            };
            /*   angular.forEach($CONFIG.$ROLES, function (value, key) {
             var srt_role = value.substr(0, 3);
             if (data.hasOwnProperty(srt_role) && data[srt_role]) {
             $authUser[value] = data[srt_role];
             }
             })*/
            if (data.hasOwnProperty('admin'))
                $authUser['admin'] = data.admin;
        };
        return $authModel;
    }])