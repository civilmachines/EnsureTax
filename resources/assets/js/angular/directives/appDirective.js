/**
 * Created by Admin on 4/6/2017.
 */
app

    .directive('hsHeader', function () {
        return {
            restrict: 'E', //This menas that it will be used as an attribute and NOT as an element. I don't like creating custom HTML elements
            replace: true,
            // scope: {user: '='},
            templateUrl: "header.html",
            controller: ['$state', '$scope', '$state', '$auth', '$uibModal', '$mdSidenav', '$appModel', function ($state, $scope, $state, $auth, $uibModal, $mdSidenav, $appModel) {
                angular.extend($scope, {
                    logout: function () {
                        $appModel.logout();
                    },
                    signin: function () {
                        if (!$auth.isAuthenticated()) {
                            $uibModal.open({
                                templateUrl: 'authentication.html',
                                controller: 'taxTypeCtrl',
                                windowClass: 'two-login',
                            })
                            //   change route after modal result
                                .result.then(function () {
                                // change route after clicking OK button
                                $state.go($state.$current.name);
                            }, function () {
                                // change route after clicking Cancel button or clicking background
                                $state.go($state.$current.name);
                            })
                            // $state.go($state.current.name + '.auth');
                        }
                    },
                    toggleLeft: buildToggler('left'),
                    toggleRight: buildToggler('right'),
                })
                function buildToggler(componentId) {
                    return function () {
                        $mdSidenav(componentId).toggle();
                    };
                }
            }]
        }
    })
    .directive('hsFooter', function () {
        return {
            restrict: 'E', //This menas that it will be used as an attribute and NOT as an element. I don't like creating custom HTML elements
            replace: true,
            templateUrl: "footer.html",
            controller: ['$scope', '$filter', function ($scope, $filter) {
                // Your behaviour goes here :)
            }]
        }
    })

    // home page fix scroll
    .directive("scroll", ['$window', '$timeout', function ($window, $timeout) {
        return function (scope, element, attrs) {
            angular.element($window).bind("scroll", function () {
                if (this.pageYOffset >= 60) {
                    scope.nav_fixed = true;
                    if (this.pageYOffset >= 100) {
                        scope.show_fixed = true;
                    }
                } else {
                    scope.nav_fixed = false;
                    scope.show_fixed = false;
                }
                scope.$apply();
            });
        };
    }])
    .directive('ngAutocomplete', ['$parse', function ($parse) {
        function convertPlaceToFriendlyObject(place) {
            var result = undefined;
            if (place) {
                result = {};
                for (var i = 0, l = place.address_components.length; i < l; i++) {
                    if (i == 0) {
                        result.searchedBy = place.address_components[i].types[0];
                    }
                    result[place.address_components[i].types[0]] = place.address_components[i].long_name;
                }
                result.formattedAddress = place.formatted_address;
                result.place_id = place.place_id;
                result.lat = place.geometry.location.lat();
                result.lng = place.geometry.location.lng();
                result.geometry = place.geometry.viewport;
                result.place = place.vicinity;
            }
            return result;
        }

        return {
            restrict: 'A',
            require: 'ngModel',
            link: function ($scope, $element, $attrs, $ctrl) {

                if (!angular.isDefined($attrs.details)) {
                    throw '<ng-autocomplete> must have attribute [details] assigned to store full address object';
                }

                var getDetails = $parse($attrs.details);
                var setDetails = getDetails.assign;
                var getOptions = $parse($attrs.options);
                //options for autocomplete
                var opts;
                //convert options provided to opts
                var initOpts = function () {
                    opts = {};
                    if (angular.isDefined($attrs.options)) {
                        var options = getOptions($scope);
                        if (options.types) {
                            opts.types = options.types;
                            // opts.types.push(options.types);
                        }
                        // if (options.bounds) {
                        //     opts.bounds = options.bounds;
                        // }
                        // if (options.strictBounds) {
                        //     opts.strictBounds = options.strictBounds;
                        // }
                        if (options.country) {
                            opts.componentRestrictions = {
                                country: options.country
                            };
                        }
                    }
                };

                //create new autocomplete
                //reinitializes on every change of the options provided
                var newAutocomplete = function () {
                    var gPlace = new google.maps.places.Autocomplete($element[0], opts);
                    google.maps.event.addListener(gPlace, 'place_changed', function () {
                        $scope.$apply(function () {
                            var place = gPlace.getPlace();
                            var details = convertPlaceToFriendlyObject(place);
                            setDetails($scope, details);
                            $ctrl.$setViewValue(details.formattedAddress);
                            $ctrl.$validate();
                        });
                        if ($ctrl.$valid && angular.isDefined($attrs.validateFn)) {
                            $scope.$apply(function () {
                                $scope.$eval($attrs.validateFn);
                            });
                        }
                    });
                };
                newAutocomplete();

//                        $ctrl.$validators.parse = function (value) {
//                            var details = getDetails($scope);
//                            var valid = ($attrs.required == true && details != undefined && details.lat != undefined) ||
//                                    (!$attrs.required && (details == undefined || details.lat == undefined) && $element.val() != '');
//                            return valid;
//                        };

                $element.on('keypress', function (e) {
                    // prevent form submission on pressing Enter as there could be more inputs to fill out
                    if (e.which == 13) {
                        e.preventDefault();
                    }
                });

                //watch options provided to directive
                if (angular.isDefined($attrs.options)) {
                    $scope.$watch($attrs.options, function () {
                        initOpts();
                        newAutocomplete();
                    });
                }

                // user typed something in the input - means an intention to change address, which is why
                // we need to null out all fields for fresh validation
                $element.on('keyup', function (e) {
                    //          chars 0-9, a-z                        numpad 0-9                   backspace         delete           space
                    if ((e.which >= 48 && e.which <= 90) || (e.which >= 96 && e.which <= 105) || e.which == 8 || e.which == 46 || e.which == 32) {
                        var details = getDetails($scope);
                        if (details != undefined) {
                            for (var property in details) {
                                if (details.hasOwnProperty(property) && property != 'formattedAddress') {
                                    delete details[property];
                                }
                            }
                            setDetails($scope, details);
                        }
                        if ($ctrl.$valid) {
                            $scope.$apply(function () {
                                $ctrl.$setValidity('parse', false);
                            });
                        }
                    }
                });
            }
        };
    }])
    .directive('showErrors', ['$timeout', 'showErrorsConfig', function ($timeout, showErrorsConfig) {
        var getShowSuccess, link;
        getShowSuccess = function (options) {
            var showSuccess;
            showSuccess = showErrorsConfig.showSuccess;
            if (options && options.showSuccess != null) {
                showSuccess = options.showSuccess;
            }
            return showSuccess;
        };
        link = function (scope, el, attrs, formCtrl) {
            var blurred, inputEl, inputName, inputNgEl, options, showSuccess, toggleClasses;
            blurred = false;
            options = scope.$eval(attrs.showErrors);
            showSuccess = getShowSuccess(options);
            inputEl = el[0].querySelector('[name]');
            inputNgEl = angular.element(inputEl);
            inputName = inputNgEl.attr('name');
            if (!inputName) {
                throw 'show-errors element has no child input elements with a \'name\' attribute';
            }
            inputNgEl.bind('blur', function () {
                blurred = true;
                return toggleClasses(formCtrl[inputName].$invalid);
            });
            scope.$watch(function () {
                return formCtrl[inputName] && formCtrl[inputName].$invalid;
            }, function (invalid) {
                if (!blurred) {
                    return;
                }
                return toggleClasses(invalid);
            });
            scope.$on('show-errors-check-validity', function () {
                return toggleClasses(formCtrl[inputName].$invalid);
            });
            scope.$on('show-errors-reset', function () {
                return $timeout(function () {
                    el.removeClass('has-error');
                    el.removeClass('has-success');
                    return blurred = false;
                }, 0, false);
            });
            return toggleClasses = function (invalid) {
                el.toggleClass('has-error', invalid);
                if (showSuccess) {
                    return el.toggleClass('has-success', !invalid);
                }
            };
        };
        return {
            restrict: 'A',
            require: '^form',
            compile: function (elem, attrs) {
                if (!elem.hasClass('md-form')) {
                    throw 'show-errors element does not have the \'md-form\' class';
                }
                return link;
            }
        };
    }])
    .directive("ngUnique", ['$authModel', function ($authModel) {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                element.bind('blur', function (e) {
                    if (!ngModel || !element.val())
                        return;
                    var keyProperty = scope.$eval(attrs.ngUnique);
                    var currentValue = element.val();
                    $authModel.checkExist(keyProperty.property, currentValue)
                        .then(function (unique) {

                            //Ensure value that being checked hasn't changed
                            //since the Ajax call was made
                            var status = unique.data.isUnique ? false : true;
                            if (currentValue === element.val()) {
                                ngModel.$setValidity('unique', status);
                                // scope.$broadcast('show-errors-check-validity');
                            }
                        }).catch(function (response) {
                        if (response.data.status_code === 422) {
                            angular.forEach(response.data.errors, function (value, key) {
                                toastr.error(key + ': ' + value);
                            });
                        }
                        toastr.error(response.data.message);
                    });
                });
            }
        }
    }])
    .directive("ngExist", ['$authModel', function ($authModel) {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                element.bind('blur', function (e) {
                    if (!ngModel || !element.val())
                        return;
                    var keyProperty = scope.$eval(attrs.ngExist);
                    var currentValue = element.val();
                    $authModel.checkExist(keyProperty.property, currentValue)
                        .then(function (data) {
                            console.log(data)
                            //Ensure value that being checked hasn't changed
                            //since the Ajax call was made
                            if (currentValue === element.val()) {
                                ngModel.$setValidity('exist', data.data.isUnique);
                                console.log('exist', data.data.isUnique)
                                // scope.$broadcast('show-errors-check-validity');
                            }
                        }).catch(function (response) {
                        if (response.data.status_code === 422) {
                            angular.forEach(response.data.errors, function (value, key) {
                                toastr.error(key + ': ' + value);
                            });
                        }
                        toastr.error(response.data.message);
                    });
                });
            }
        }
    }])
    .directive("ngReset", ['$authModel', '$sce', '$auth', '$state', '$compile', '$timeout', function ($authModel, $sce, $auth, $state, $compile, $timeout) {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                element.bind('blur', function (e) {
                    if (!ngModel || !element.val())
                        return;
                    var currentValue = element.val();
                    if (attrs.pattern.test(currentValue)) {
                        $authModel.resetRequest(scope.user)
                            .then(function (result) {
                                //Ensure value that being checked hasn't changed
                                //since the Ajax call was  console.log(data);
                                if (result.data.success) {
                                    scope.show = false;
                                    ngModel.$setValidity('reset', true);
                                    scope.confirm = true;
                                } else {
                                    scope.confirm = false;
                                    scope.show = true;
                                    scope.error = 'danger';
                                    if (result.data.hasOwnProperty('activated') && !result.data.activated) {
                                        scope.message = $sce.trustAsHtml(result.data.message);
                                        var html = $compile('<br><span><a href="javascript:void(0);" data-ng-model="user.email" ng-click="resent()" remove-me>Activate Now</a></span>')(scope);
                                        html.insertAfter(document.getElementById('message'));
                                    } else {
                                        scope.message = $sce.trustAsHtml(result.data.message);
                                    }
                                    toastr.error(result.data.message);
                                    ngModel.$setValidity('reset', false);
                                }
                            })
                            .catch(function (response) {
                                ngModel.$setValidity('reset', false);
                                if (response.data.status_code === 422) {
                                    angular.forEach(response.data.errors, function (value, key) {
                                        toastr.error(key + ': ' + value);
                                    });
                                } else {
                                    toastr.error(response.data.message);
                                }
                            });
                    }
                });
            }
        }
    }])
    .directive("ngValid", ['$authModel', '$sce', '$auth', 'toastr', function ($authModel, $sce, $auth, toastr) {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                element.bind('blur', function (e) {
                    if (!ngModel || !element.val())
                        return;
                    var currentValue = element.val();
                    $authModel.checkOTP(scope.user)
                        .then(function (data) {
                            //Ensure value that being checked hasn't changed
                            //since the Ajax call was  console.log(data);
                            if (data.data.success) {
                                scope.show = false;
                                ngModel.$setValidity('valid', true);
                            } else {
                                scope.show = true;
                                scope.error = 'danger';
                                scope.message = $sce.trustAsHtml(data.data.message);
                                toastr.error(data.data.message);
                                ngModel.$setValidity('valid', false);
                            }
                            // if (currentValue === element.val()) {
                            //     ngModel.$setValidity('valid', data.data.isUnique);
                            //     // scope.$broadcast('show-errors-check-validity');
                            // }
                        }).catch(function (response) {
                        if (response.data.status_code === 422) {
                            angular.forEach(response.data.errors, function (value, key) {
                                toastr.error(key + ': ' + value);
                            });
                        }
                        toastr.error(response.data.message);
                    });
                });
            }
        }
    }])
    .directive('compareTo', function () {
        return {
            require: "ngModel",
            scope: {
                otherModelValue: "=compareTo"
            },
            link: function (scope, element, attributes, ngModel) {
                ngModel.$validators.compareTo = function (modelValue) {
                    return modelValue == scope.otherModelValue;
                };
                scope.$watch("otherModelValue", function () {
                    ngModel.$validate();
                });
            }
        };

    })

   