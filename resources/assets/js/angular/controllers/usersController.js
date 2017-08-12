/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


angular.module("101housing").requires.push('usersTemplates');
app
    .controller('buyerCtrl', ['$scope', '$rootScope', '$state', '$CONFIG', '$filter', '$appModel', function ($scope, $rootScope, $state, $CONFIG, $filter, $appModel) {
        angular.extend($scope, {
            options: {
                country: 'IN',
            },
            // requirement: {
            //     property_type: 16,
            //     // furnished: $CONFIG.FURNISHED_TYPE_ID,
            //     // rooms: '1 BHK',
            // },
            close: function () {
                $scope.$dismiss();
            },
            changeType: function () {
                $scope.categoryList = $filter('parseParent')($appModel.loadMaster, $scope.requirement.property_type);
            },
            postRequirement: function () {
                if ($scope.requirementForm.$valid) {
                    var category = $filter('filter')($appModel.loadMaster, {value: $scope.requirement.property_type})
                    var value = 'lastname=' + $scope.requirement.name + '&firstname=' + $scope.requirement.name;
                    value += '&email=' + $scope.requirement.email + '&mobile=' + $scope.requirement.contact;
                    value += '&label:Price=' + $scope.requirement.price + '&label:Property_Type=' + $scope.requirement.rooms;
                    value += '&label:Location=' + $scope.requirement.plocation;
                    value += '&label:Category=' + category[0].text;
                    value += '&label:Furnished_Status=' + $scope.requirement.furnished;
                    value += '&leadsource=Buyer&name="101Housing-Buyers"';
                    value += '&publicid=6a594ef8eed58e3e5dab0fe4ecb83bf9';
                    $appModel.progressBar(true, $scope);
                    postRequirement(value);

                }
            }
        })
        $scope.$watchCollection(function () {
            return [$appModel.loadMaster]
        }, function (newValue) {
            if ($appModel.loadMaster.length > 0) {
                var loadMaster = $filter('parseParent')($appModel.loadMaster, 0);
                $scope.proptypeList = loadMaster.prop_types;
                $scope.categoryList = loadMaster.residential;
                $scope.furnishList = loadMaster.furnished;
                $scope.requirement = {
                    property_type: $scope.proptypeList[0].value,
                    rooms: $scope.categoryList[1].text,
                }
            }
            // if ($appModel.loadCity)
            //     $scope.cityList = $appModel.loadCity;
        });

        function postRequirement(data) {
            $appModel.crmPost(data).then(function (result) {
                if (result.data.success) {
                    $scope.show = true;
                }
                $appModel.progressBar(false, $scope);
            });
        }

    }])
    .controller('ownerCtrl', ['$scope', '$rootScope', '$state', '$CONFIG', '$filter', '$appModel', function ($scope, $rootScope, $state, $CONFIG, $filter, $appModel) {
        angular.extend($scope, {
            options: {
                country: 'IN',
            },
            owner: {
                property_type: 16,
                // furnished: $CONFIG.FURNISHED_TYPE_ID,
                // rooms: '1 BHK',
            },
            close: function () {
                $scope.$dismiss();
            },
            postProperty: function () {
                if ($scope.ownerForm.$valid) {
                    var category = $filter('filter')($appModel.loadMaster, {value: $scope.owner.property_type})
                    var value = 'lastname=' + $scope.owner.name + '&firstname=' + $scope.owner.name;
                    value += '&email=' + $scope.owner.email + '&mobile=' + $scope.owner.contact;
                    value += '&label:Price=' + $scope.owner.price + '&label:Property_Type=' + $scope.owner.rooms;
                    value += '&label:Location=' + $scope.owner.plocation;
                    value += '&label:Category=' + category[0].text;
                    value += '&label:Furnished_Status=' + $scope.owner.furnished;
                    value += '&leadsource=101H-Owner&name="101Housing-Buyers"';
                    value += '&publicid=6a594ef8eed58e3e5dab0fe4ecb83bf9';
                    $appModel.progressBar(true, $scope);
                    postProperty(value)
                }
            },
            changeType: function () {
                $scope.categoryList = $filter('parseParent')($appModel.loadMaster, $scope.owner.property_type);
            },
        })
        $scope.$watchCollection(function () {
            return [$appModel.loadMaster]
        }, function (newValue) {
            if ($appModel.loadMaster.length > 0) {
                var loadMaster = $filter('parseParent')($appModel.loadMaster, 0);
                $scope.proptypeList = loadMaster.prop_types;
                $scope.categoryList = loadMaster.residential;
                $scope.furnishList = loadMaster.furnished;
                $scope.owner = {
                    property_type: $scope.proptypeList[0].value,
                    rooms: $scope.categoryList[1].text,
                }
            }
            // if ($appModel.loadCity)
            //     $scope.cityList = $appModel.loadCity;
        });

        function postProperty(data) {
            $appModel.crmPost(data).then(function (result) {
                if (result.data.success) {
                    $scope.show = true;
                    $appModel.progressBar(false, $scope);
                }
            });
        }

    }])