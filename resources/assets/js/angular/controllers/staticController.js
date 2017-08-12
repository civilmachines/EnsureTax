/**
 * Created by Admin on 4/7/2017.
 */
angular.module("101housing").requires.push('staticTemplates');
app
    .controller('contactController', ['$scope', '$rootScope', '$state', '$appModel', function ($scope, $rootScope, $state, $appModel) {
        angular.extend($scope, {
            contactUs: function () {
                if ($scope.contactForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    /*var value = 'lastname=' + $scope.contact.name + '&firstname=' + $scope.contact.name;
                    value += '&email=' + $scope.contact.email + '&mobile=' + $scope.contact.contact;
                    value += '&description=' + $scope.contact.message + '&leadsource=Contact Us';
                    value += '&name="ensureTax"&department=ensureTax&publicid=6a594ef8eed58e3e5dab0fe4ecb83bf9';*/
                    // console.log(value);
                    $appModel.contactUS($scope.contact).then(function (result) {
                        if (result.data.success === true && result.data.message === 'success') {
                            $scope.show = true;
                            $appModel.progressBar(false, $scope);
                        }
                    });
                }
            }
        });
    }]);



