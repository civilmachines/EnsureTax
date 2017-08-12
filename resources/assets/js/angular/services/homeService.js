/**
 * Created by Admin on 4/11/2017.
 */
app
    .factory('$homeModel', ['$http', '$API', '$appModel', function ($http, $API, $appModel) {
        var $homeModel = {};
        $homeModel.loadMaster = {};
        $homeModel.getCategory = function () {
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_GET_CATEGORY,
                cache: true,
                method: 'GET',
            }).success(function (response) {
                if (response.success) {
                    $homeModel.loadMaster = response.category;
                }
            }).error(function (data, status, headers) {
                $appModel.error(data, status);
                // return console.log(data);
            });
        };

        $homeModel.itrdetails = function (data) {
            console.log(data);
            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: $API.API_POST_ITR1_DETAILS,
                method: 'POST',
                data: data,
                // transformRequest: angular.identity
            }).success(function (response) {
                return response;
            }).error(function (data, status, headers) {
                return $appModel.error(data, status);
            });
        };


        return $homeModel;
    }])
