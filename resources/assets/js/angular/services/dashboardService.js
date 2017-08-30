/**
 * Created by Admin on 4/18/2017.
 */
app
    .factory('$dashboardModel', ['$http', '$API', '$appModel', '$authModel', '$CONFIG', function ($http, $API, $appModel, $authModel, $CONFIG) {
        var $dashboardModel = {};
        // $dashboardModel.loadMasterCategory = {};
        // loadAllCategory();
        $dashboardModel.itrList = {};
        $dashboardModel.corpItrList = {};
        $dashboardModel.GSTFiles = {};

        $dashboardModel.postUserProfile = function (data) {
            return $http({
                headers: {
                    'Content-Type': undefined
                },
                url: $API.API_POST_USER_PROFILE,
                method: 'POST',
                data: data,
                transformRequest: angular.identity,
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.getUserProfile = function () {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_GET_USER_PROFILE,
                method: 'GET',
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.changePassword = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_CHANGE_PASSWORD,
                method: 'POST',
                data: data
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.ITRList = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_ITR_LIST,
                method: 'GET',
                params: data
            }).success(function (response) {
                if (response.success) {
                    $dashboardModel.itrList = response.data;
                }
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.editITR = function (id) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_GET_EDIT_ITR + id,
                method: 'GET'
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.itrStatus = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_ITR_STATUS,
                method: 'POST',
                data: data
            }).success(function (response) {
                if (response.success) {
                    $dashboardModel.itrList = response.data;

                }
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.selectITR = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_SELECT_ITR,
                method: 'POST',
                data: data
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };


        $dashboardModel.itr1details = function (data) {
            return $http({
                headers: {
                    'Content-Type': undefined
                },
                url: $API.API_POST_ITR1_DETAILS,
                method: 'POST',
                data: data,
                transformRequest: angular.identity
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.itr2details = function (data) {
            return $http({
                headers: {
                    'Content-Type': undefined
                },
                url: $API.API_POST_ITR2_DETAILS,
                method: 'POST',
                data: data,
                transformRequest: angular.identity
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.itr3details = function (data) {
            return $http({
                headers: {
                    'Content-Type': undefined
                },
                url: $API.API_POST_ITR3_DETAILS,
                method: 'POST',
                data: data,
                transformRequest: angular.identity
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.itr4details = function (data) {
            return $http({
                headers: {
                    'Content-Type': undefined
                },
                url: $API.API_POST_ITR4_DETAILS,
                method: 'POST',
                data: data,
                transformRequest: angular.identity
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.deleteITRImage = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_DELET_IMAGE,
                method: 'DELETE',
                data: data,
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return console.log(data);
            });
        };

        $dashboardModel.corporateDetails = function (data) {
            return $http({
                headers: {
                    'Content-Type': undefined
                },
                url: $API.API_POST_CORPORATE_DETAILS,
                method: 'POST',
                data: data,
                transformRequest: angular.identity
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };
        $dashboardModel.itr5details = function (data) {
            return $http({
                headers: {
                    'Content-Type': undefined
                },
                url: $API.API_POST_ITR5_DETAILS,
                method: 'POST',
                data: data,
                transformRequest: angular.identity
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.CorporateITRList = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_CORP_ITR_LIST,
                method: 'GET',
                params: data
            }).success(function (response) {
                if (response.success) {
                    $dashboardModel.corpItrList = response.data;
                }
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.editCorpITR = function (id) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_EDIT_CORPORATE_ITR + id,
                method: 'GET'
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.deleteCoprporateImage = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_DELET_CORP_IMAGE,
                method: 'DELETE',
                data: data
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return console.log(data);
            });
        };

        $dashboardModel.corpItrStatus = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_CORP_ITR_STATUS,
                method: 'POST',
                data: data
            }).success(function (response) {
                if (response.success) {
                    $dashboardModel.corpItrList = response.data;
                }
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.gstFiles = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_GET_GST_FILES,
                method: 'GET',
                params: data
            }).success(function (response) {
                if (response.success) {
                    $dashboardModel.GSTFiles = response.data;
                }
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.editGST = function (id) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_EDIT_GST_FILE + id,
                method: 'GET'
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };

        $dashboardModel.uploadGST = function (data) {
            return $http({
                headers: {
                    'Content-Type': undefined
                },
                url: $API.API_UPLOAD_GST_FILES,
                method: 'POST',
                data: data,
                transformRequest: angular.identity
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };
        $dashboardModel.deleteGSTImage = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_DELETE_GST_IMAGE,
                method: 'DELETE',
                data: data
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return console.log(data);
            });
        };

        $dashboardModel.gstStatus = function (data) {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_GST_STATUS,
                method: 'POST',
                data: data
            }).success(function (response) {
                if (response.success) {
                    $dashboardModel.corpItrList = response.data;
                }
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };





        $dashboardModel.nextTab = function (scope) {
            var index = (scope.tabs.selectedIndex == scope.tabs.max) ? 0 : scope.tabs.selectedIndex + 1;
            scope.tabs.selectedIndex = index;
        };
        $dashboardModel.backTab = function (scope) {
            var index = (scope.tabs.selectedIndex == scope.tabs.max) ? scope.tabs.selectedIndex - 1 : scope.tabs.selectedIndex - 1;
            scope.tabs.selectedIndex = index;
        };

        /*  function loadAllCategory() {
         return $http({
         headers: {
         'Content-Type': 'application/json'
         },
         url: $API.API_AUTH_GET_ALL_CATEGORY,
         method: 'GET'
         }).success(function (response) {
         if (response.success) {
         $dashboardModel.loadMasterCategory = response.data;
         }
         return response;
         }).error(function (data, status, headers) {
         return console.log(data);
         });
         }*/

        return $dashboardModel;
    }])