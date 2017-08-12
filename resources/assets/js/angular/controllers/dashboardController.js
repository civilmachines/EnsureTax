/**
 * Created by Admin on 4/18/2017.
 */

angular.module("101housing").requires.push('dashboardTemplates');
app
    .constant()
    .controller('dashboardCtrl', ['$scope', '$rootScope', '$state', '$stateParams', '$filter', '$dashboardModel', function ($scope, $rootScope, $state, $stateParams, $filter, $dashboardModel) {
    }])
    .controller('passwordCtrl', ['$scope', '$dashboardModel', '$appModel', 'toastr', function ($scope, $dashboardModel, $appModel, toastr) {
        angular.extend($scope, {
            changePassword: function () {
                if ($scope.passwordForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    changePassword($scope.user);
                }
            }
        });

        function changePassword(data) {
            $dashboardModel.changePassword(data).then(function (result) {
                if (result.data.success) {
                    toastr.success(result.data.message, 'Success!');
                    $scope.user = {};
                    $scope.passwordForm.$setPristine();
                    $scope.passwordForm.$setUntouched();
                } else {
                    toastr.warning(result.data.message, 'Warning!');
                }
                $appModel.progressBar(false, $scope);
            });
        }
    }])
    .controller('profileCtrl', ['$scope', '$rootScope', '$dashboardModel', '$filter', '$CONFIG', '$appModel', 'toastr', function ($scope, $rootScope, $dashboardModel, $filter, $CONFIG, $appModel, toastr) {
        getUserProfile();
        angular.extend($scope, {
            postProfile: function () {
                var myFormData = new FormData();
                angular.forEach($scope.profile.pan_image, function (value, key) {
                    myFormData.append('pan_image[]', value._file);
                });
                angular.forEach($scope.profile.previous_return_image, function (value, key) {
                    myFormData.append('previous_return_image[]', value._file);
                });
                angular.forEach($scope.profile.bank_statement, function (value, key) {
                    myFormData.append('bank_statement[]', value._file);
                });
                myFormData.append('ifsc_code', $scope.profile.ifsc_code);
                myFormData.append('name', $scope.profile.name);
                // myFormData.append('email', $scope.profile.email);
                // myFormData.append('mobile', $scope.profile.mobile);
                myFormData.append('residential_status', $scope.profile.residential_status);
                myFormData.append('employer_category', $scope.profile.employer_category);
                myFormData.append('address', $scope.profile.address);
                postUserProfile(myFormData);
            },
            btnText: 'Update',

            remove: function (item, obj) {
                if (confirm("Are You Sure to Delete?"))
                    if (!item.agent_id) {
                        var index = $scope.profile[obj].indexOf(item);
                        $scope.profile[obj].splice(index, 1);
                    } else {
                        var data = {
                            agent_id: item.agent_id,
                            category: item.category,
                            img_name: item.image
                        };
                        deleteImage(data, item, obj);
                    }
            }
        });

        function deleteImage(data, item, obj) {
            $dashboardModel.deleteITRImage(data).then(function (result) {
                if (result.data.success) {
                    var index = $scope.profile[obj].indexOf(item);
                    $scope.profile[obj].splice(index, 1);
                }
            })
        }

        function getUserProfile() {
            $dashboardModel.getUserProfile().then(function (result) {
                if (result.data.success) {
                    var profile = result.data.data;
                    angular.extend($scope, {
                        profile: {
                            name: profile.name,
                            email: profile.email,
                            mobile: profile.mobile,
                            residential_status: profile.residential_status,
                            employer_category: profile.employer_category,
                            ifsc_code: profile.ifsc_code == "null" ? '' : profile.ifsc_code,
                            address: profile.address == "null" ? '' : profile.address,
                            pan_image: $filter('parseImageCategory')(profile.images, $CONFIG.ITR_Image_Category.pan_image),
                            previous_return_image: $filter('parseImageCategory')(profile.images, $CONFIG.ITR_Image_Category.previous_return_image),
                            bank_statement: $filter('parseImageCategory')(profile.images, $CONFIG.ITR_Image_Category.bank_statement)
                        }
                    })
                }
            });
        }

        function postUserProfile(myFormData) {
            $dashboardModel.postUserProfile(myFormData).then(function (result) {
                if (result.data.success) {
                    $appModel.progressBar(false, $scope);
                    toastr.success(result.data.message, 'Success!');
                }
            });
        }

        $scope.$watchCollection(function () {
            return $appModel.loadMasterCategory;
        }, function (newval, oldval) {
            if ($appModel.loadMasterCategory.length > 0) {
                var loadMaster = $filter('parseParent')($appModel.loadMasterCategory, 0);
                $scope.residence = loadMaster.residential_status;
                $scope.employer = loadMaster.employer_category;
            }
        });
    }])
    .controller('ITRListCtrl', ['$scope', '$state', '$filter', 'resolveData', 'toastr', '$appModel', '$dashboardModel', 'localStorageService', function ($scope, $state, $filter, resolveData, toastr, $appModel, $dashboardModel, localStorageService) {
        var data = {
            ITR: localStorageService.get('itr_id')
        };
        if (data.hasOwnProperty('ITR') && data.ITR > 0)
            $dashboardModel.selectITR(data).then(function (result) {
                if (result.data.success) {
                    $state.go('add-itr', {id: result.data.data});
                    localStorageService.clearAll();
                }
            });
        if (!$dashboardModel.itrList.length > 0) {
            // $scope.show = true;
            // $scope.loader = true;
            $scope.not_found = true;
            $scope.message = 'Data Not Found';
            return false;
        }
        // $scope.disabled = true;
        angular.extend($scope, {
            filterdata: {
                minValue: 0,
                maxValue: 100000
            },
            status: {},
            itr: {selected: []},
            toggle: function (item, list) {
                toggleCheckbox(parseInt(item), list);
            },
            exists: function (item, list) {
                return list.indexOf(parseInt(item)) > -1;
            },
            editTabDialog: function (row) {
                $state.go('add-itr', {id: row.id});
            },
            sortby: function (item, order) {
                order = order ? 'desc' : 'asc';
                var data = angular.extend($scope.filterdata, {orderby: item, order: order});
                itrList(data, item, order);
            },

            changeStatus: function () {
                if ($scope.itr && $scope.itr.status > 0 && $scope.itr.selected.length > 0) {
                    var data = {
                        id: $scope.itr.selected,
                        status: $scope.itr.status
                    };
                    itrStatus(data);
                } else if (!$scope.itr.selected.length > 0 && $scope.itr) {
                    var status = {
                        status: $scope.itr.status
                    };
                    itrList(status);
                }
            }
        });
        function itrList(data) {
            data = {
                status: data.status,
                orderBy: data.orderby,
                order: data.order
            };
            $dashboardModel.ITRList(data).then(function (result) {
                if (!result.data.data.length > 0) {
                    $scope.no_item = true;
                    $scope.hide_list = true;
                } else {
                    $scope.hide_list = false;
                    $scope.no_item = false;
                }
            })
        }

        $scope.$watchCollection(function () {
            return $dashboardModel.itrList;
        }, function (newval, oldval) {
            if ($dashboardModel.itrList.length > 0) {
                $scope.ITR = $dashboardModel.itrList;
                $scope.not_found = $scope.ITR.length > 0 ? false : true;
                $scope.directives = $scope.ITR.length > 0 ? true : false;
            }
        });

        function toggleCheckbox(item, list) {
            var idx = list.indexOf(item);
            if (idx > -1) {
                list.splice(idx, 1);
            } else {
                list.push(item);
            }
        }

        function itrStatus(data) {
            $dashboardModel.itrStatus(data).then(function (result) {
                if (result.data.success) {
                    toastr.success("status Updated", 'Success!');
                }
                $appModel.progressBar(false, $scope);
            })
        }

        $scope.$watchCollection(function () {
            return $appModel.loadMasterCategory;
        }, function (newval, oldval) {
            if ($appModel.loadMasterCategory.length > 0) {
                var loadMaster = $filter('parseParent')($appModel.loadMasterCategory, 0);
                $scope.status = loadMaster.application_status;
            }
        });
    }])
    .controller('addITRCtrl', ['$scope', '$rootScope', '$state', '$stateParams', '$CONFIG', 'toastr', '$filter', '$API', '$timeout', '$appModel', '$authModel', 'resolveData', '$dashboardModel', function ($scope, $rootScope, $state, $stateParams, $CONFIG, toastr, $filter, $API, $timeout, $appModel, $authModel, resolveData, $dashboardModel) {
        getUserProfile();
        angular.extend($scope, {
            tabs: {
                max: 2,
                selectedIndex: 0,
                disable: true
            },
            first: true,
            showBtns: false,
            lastFile: null,
            dzMethods: {},
            dzOptions: {
                url: $API.POST_PROPERTY_IMAGE,
                dictDefaultMessage: '<i class="fa fa-plus"></i> Drop image or click to choose image',
                parallelUploads: 4,
                autoProcessQueue: false,
                acceptedFiles: 'image/jpeg, images/jpg, image/png',
                addRemoveLinks: true,
                uploadMultiple: true,
                dictCancelUpload: '',
                maxFilesize: '2'
                // maxFiles: 4,
            },
            dzCallbacks: {
                'addedfile': function (file, xhr, formData) {
                    $scope.showBtns = true;
                    $scope.lastFile = true;
                },
                'sendingmultiple': function (file, xhr, formData) {
                    formData.append("_token", csrfToken);
                    formData.append("pid", $scope.property.id);
                },
                'successmultiple': function (file, result) {
                    if (result.success) {
                        $scope.pop_gallery = {images: result.image};
                    }
                },
                'error': function (file, xhr) {
                    if (file.size > 2 * 1000) {
                        alert(xhr);
                    }
                    console.warn('File failed to upload from dropzone 2.', file, xhr);
                },
                'completemultiple': function (file) {
                    angular.forEach(file, function (value, key) {
                        $scope.dzMethods.removeFile(value);
                    });
                },
                'queuecomplete': function (file) {
                    $scope.showBtns = false;
                    $scope.lastFile = null;
                }
            },
            remove: function (item, obj) {
                if (confirm("Are you sure?"))
                    if (!item.itr_id && !item.agent_id) {
                        if (!obj.pan_image && !obj.previous_return_image && !obj.bank_statement) {
                            var index = $scope.itr[obj].indexOf(item);
                            $scope.itr[obj].splice(index, 1);
                        }
                    } else if (!item.agent_id && !item.itr_id && obj.pan_image || obj.previous_return_image || obj.bank_statement) {
                        var index = $scope.profile[obj].indexOf(item);
                        $scope.profile[obj].splice(index, 1);
                    }
                    else {
                        var data = {
                            itr_id: item.itr_id,
                            agent_id: item.agent_id,
                            category: item.category,
                            img_name: item.image
                        };
                        deleteImage(data, item, obj);
                    }
            },

            postProfile: function () {
                var myFormData = new FormData();
                angular.forEach($scope.user.pan_image, function (value, key) {
                    myFormData.append('pan_image[]', value._file);
                });
                angular.forEach($scope.user.previous_return_image, function (value, key) {
                    myFormData.append('previous_return_image[]', value._file);
                });
                angular.forEach($scope.user.bank_statement, function (value, key) {
                    myFormData.append('bank_statement[]', value._file);
                });
                myFormData.append('ifsc_code', $scope.user.ifsc_code);
                myFormData.append('name', $scope.user.name);
                myFormData.append('residential_status', $scope.user.residential_status);
                myFormData.append('employer_category', $scope.user.employer_category);
                myFormData.append('address', $scope.user.address);
                postUserProfile(myFormData);
            },
            submitITR1: function () {
                if ($scope.taxForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    var myFormData = new FormData();
                    angular.forEach($scope.itr.form_16, function (value, key) {
                        myFormData.append('form_16[]', value._file);
                    });
                    angular.forEach($scope.itr.loan_certificate, function (value, key) {
                        myFormData.append('loan_certificate[]', value._file);
                    });
                    angular.forEach($scope.itr.interest_certificate, function (value, key) {
                        myFormData.append('interest_certificate[]', value._file);
                    });
                    angular.forEach($scope.itr.claim_80C, function (value, key) {
                        myFormData.append('claim_80C[]', value._file);
                    });
                    angular.forEach($scope.itr.claim_80D, function (value, key) {
                        myFormData.append('claim_80D[]', value._file);
                    });
                    angular.forEach($scope.itr.claim_80G, function (value, key) {
                        myFormData.append('claim_80G[]', value._file);
                    });
                    angular.forEach($scope.itr.other_deduction, function (value, key) {
                        myFormData.append('other_deduction[]', value._file);
                    });
                    angular.forEach($scope.itr.tax_challan, function (value, key) {
                        myFormData.append('tax_challan[]', value._file);
                    });
                    angular.forEach($scope.itr.form_26A, function (value, key) {
                        myFormData.append('form_26A[]', value._file);
                    });
                    myFormData.append('ITR', $scope.itr.ITR);
                    myFormData.append('form_16_amnt', $scope.itr.form_16_amnt);
                    myFormData.append('house_income', $scope.itr.house_income);
                    myFormData.append('interest_income', $scope.itr.interest_income);
                    myFormData.append('id', $scope.itr.id);
                    saveITR1(myFormData);
                }
            },
            submitITR2: function () {
                $appModel.progressBar(true, $scope);
                var myFormData = new FormData();
                angular.forEach($scope.itr.co_owned_prop_image, function (value, key) {
                    myFormData.append('co_owned_prop_image[]', value._file);
                });
                angular.forEach($scope.itr.partner_firm_image, function (value, key) {
                    myFormData.append('partner_firm_image[]', value._file);
                });
                angular.forEach($scope.itr.capital_gain_image, function (value, key) {
                    myFormData.append('capital_gain_image[]', value._file);
                });
                angular.forEach($scope.itr.othr_source_incm_image, function (value, key) {
                    myFormData.append('othr_source_incm_image[]', value._file);
                });
                angular.forEach($scope.itr.immovable_asset_image, function (value, key) {
                    myFormData.append('immovable_asset_image[]', value._file);
                });
                angular.forEach($scope.itr.outside_income_image, function (value, key) {
                    myFormData.append('outside_income_image[]', value._file);
                });
                angular.forEach($scope.itr.assets_partner_image, function (value, key) {
                    myFormData.append('assets_partner_image[]', value._file);
                });
                angular.forEach($scope.itr.othr_cntry_tax_paid_img, function (value, key) {
                    myFormData.append('othr_cntry_tax_paid_img[]', value._file);
                });
                myFormData.append('co_owned_prop', $scope.itr.co_owned_prop ? true : false);
                myFormData.append('partner_firms', $scope.itr.partner_firms);
                myFormData.append('capital_gains', $scope.itr.capital_gains);
                myFormData.append('other_source_income', $scope.itr.other_source_income);
                myFormData.append('immovable_assets', $scope.itr.immovable_assets);
                myFormData.append('outside_income', $scope.itr.outside_income);
                myFormData.append('assets_partner', $scope.itr.assets_partner);
                myFormData.append('other_cntry_tax', $scope.itr.other_cntry_tax);
                myFormData.append('return_filing_date', $filter('date')(new Date($scope.itr.return_filing_date), 'yyyy-MM-dd'));
                myFormData.append('loss_amnt', $scope.itr.loss_amnt);
                myFormData.append('jewellery_amnt', $scope.itr.jewellery_amnt);
                myFormData.append('paintings_amnt', $scope.itr.paintings_amnt);
                myFormData.append('vehicles_amnt', $scope.itr.vehicles_amnt);
                myFormData.append('bank_amnt', $scope.itr.bank_amnt);
                myFormData.append('share_amnt', $scope.itr.share_amnt);
                myFormData.append('loan_amnt', $scope.itr.loan_amnt);
                myFormData.append('cash_amnt', $scope.itr.cash_amnt);
                myFormData.append('other_liability_amnt', $scope.itr.other_liability_amnt);
                myFormData.append('id', $scope.itr.id);
                saveITR2(myFormData);
            },
            submitITR3: function () {
                $appModel.progressBar(true, $scope);
                var myFormData = new FormData();
                angular.forEach($scope.itr.complete_finance_image, function (value, key) {
                    myFormData.append('complete_finance_image[]', value._file);
                });
                angular.forEach($scope.itr.co_owned_prop_image, function (value, key) {
                    myFormData.append('co_owned_prop_image[]', value._file);
                });
                angular.forEach($scope.itr.partner_firm_image, function (value, key) {
                    myFormData.append('partner_firm_image[]', value._file);
                });
                angular.forEach($scope.itr.capital_gain_image, function (value, key) {
                    myFormData.append('capital_gain_image[]', value._file);
                });
                angular.forEach($scope.itr.othr_source_incm_image, function (value, key) {
                    myFormData.append('othr_source_incm_image[]', value._file);
                });
                angular.forEach($scope.itr.immovable_asset_image, function (value, key) {
                    myFormData.append('immovable_asset_image[]', value._file);
                });
                angular.forEach($scope.itr.outside_income_image, function (value, key) {
                    myFormData.append('outside_income_image[]', value._file);
                });
                angular.forEach($scope.itr.assets_partner_image, function (value, key) {
                    myFormData.append('assets_partner_image[]', value._file);
                });
                angular.forEach($scope.itr.othr_cntry_tax_paid_img, function (value, key) {
                    myFormData.append('othr_cntry_tax_paid_img[]', value._file);
                });
                angular.forEach($scope.itr.audit_report_img, function (value, key) {
                    myFormData.append('audit_report_img[]', value._file);
                });
                myFormData.append('business_nature', $scope.itr.business_nature);
                myFormData.append('co_owned_prop', $scope.itr.co_owned_prop);
                myFormData.append('partner_firms', $scope.itr.partner_firms);
                myFormData.append('capital_gains', $scope.itr.capital_gains);
                myFormData.append('other_source_income', $scope.itr.other_source_income);
                myFormData.append('immovable_assets', $scope.itr.immovable_assets);
                myFormData.append('outside_income', $scope.itr.outside_income);
                myFormData.append('assets_partner', $scope.itr.assets_partner);
                myFormData.append('other_cntry_tax', $scope.itr.other_cntry_tax);
                myFormData.append('audit_report', $scope.itr.audit_report);
                myFormData.append('loss_amnt', $scope.itr.loss_amnt);
                myFormData.append('jewellery_amnt', $scope.itr.jewellery_amnt);
                myFormData.append('paintings_amnt', $scope.itr.paintings_amnt);
                myFormData.append('vehicles_amnt', $scope.itr.vehicles_amnt);
                myFormData.append('bank_amnt', $scope.itr.bank_amnt);
                myFormData.append('return_filing_date', $filter('date')(new Date($scope.itr.return_filing_date), 'yyyy-MM-dd'));
                myFormData.append('share_amnt', $scope.itr.share_amnt);
                myFormData.append('loan_amnt', $scope.itr.loan_amnt);
                myFormData.append('cash_amnt', $scope.itr.cash_amnt);
                myFormData.append('other_liability_amnt', $scope.itr.other_liability_amnt);
                myFormData.append('id', $scope.itr.id);
                saveITR3(myFormData);
            },
            submitITR4: function () {
                $appModel.progressBar(true, $scope);
                var myFormData = new FormData();
                angular.forEach($scope.itr.othr_source_incm_image, function (value, key) {
                    myFormData.append('othr_source_incm_image[]', value._file);
                });
                angular.forEach($scope.itr.immovable_asset_image, function (value, key) {
                    myFormData.append('immovable_asset_image[]', value._file);
                });
                angular.forEach($scope.itr.assets_partner_image, function (value, key) {
                    myFormData.append('assets_partner_image[]', value._file);
                });
                myFormData.append('business_nature', $scope.itr.business_nature);
                myFormData.append('turnover', $scope.itr.turnover);
                myFormData.append('net_profit', $scope.itr.net_profit);
                myFormData.append('jewellery_amnt', $scope.itr.jewellery_amnt);
                myFormData.append('paintings_amnt', $scope.itr.paintings_amnt);
                myFormData.append('vehicles_amnt', $scope.itr.vehicles_amnt);
                myFormData.append('bank_amnt', $scope.itr.bank_amnt);
                myFormData.append('share_amnt', $scope.itr.share_amnt);
                myFormData.append('loan_amnt', $scope.itr.loan_amnt);
                myFormData.append('cash_amnt', $scope.itr.cash_amnt);
                myFormData.append('other_liability_amnt', $scope.itr.other_liability_amnt);
                myFormData.append('other_source_income', $scope.itr.other_source_income);
                myFormData.append('immovable_assets', $scope.itr.immovable_assets);
                myFormData.append('assets_partner', $scope.itr.assets_partner);
                myFormData.append('id', $scope.itr.id);
                saveITR4(myFormData);
            },
            nextTab: function () {
                $dashboardModel.nextTab($scope);
            },
            backTab: function () {
                $dashboardModel.backTab($scope);
            }
        });
        if (resolveData && resolveData.data.success) {
            var ITRDetails = resolveData.data.data;
            fillITR(ITRDetails);
            $scope.tabs.disable = false;
        }
        function fillITR(ITRDetails) {
            angular.extend($scope, {
                user: {
                    name: ITRDetails.user_details.name,
                    email: ITRDetails.user_details.email,
                    mobile: ITRDetails.user_details.mobile,
                    ifsc_code: ITRDetails.user_details.ifsc_code ? ITRDetails.user_details.ifsc_code : '',
                    residential_status: ITRDetails.user_details.residential_status,
                    employer_category: ITRDetails.user_details.employer_category,
                    address: ITRDetails.user_details.address ? ITRDetails.user_details.address : '',
                    pan_image: $filter('parseImageCategory')(ITRDetails.profile_images, $CONFIG.ITR_Image_Category.pan_image),
                    previous_return_image: $filter('parseImageCategory')(ITRDetails.profile_images, $CONFIG.ITR_Image_Category.previous_return_image),
                    bank_statement: $filter('parseImageCategory')(ITRDetails.profile_images, $CONFIG.ITR_Image_Category.bank_statement)

                },
                itr: {
                    id: ITRDetails.id,
                    ITR: ITRDetails.ITR,
                    form_16_amnt: ITRDetails.form_16_amnt == 0.00 ? '' : ITRDetails.form_16_amnt,
                    house_income: ITRDetails.house_income == 0.00 ? '' : ITRDetails.house_income,
                    interest_income: ITRDetails.interest_income == 0.00 ? '' : ITRDetails.interest_income,
                    form_16: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.form_16),
                    loan_certificate: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.loan_certificate),
                    interest_certificate: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.interest_certificate),
                    claim_80C: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.claim_80C),
                    claim_80D: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.claim_80D),
                    claim_80G: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.claim_80G),
                    other_deduction: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.other_deduction),
                    tax_challan: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.tax_challan),
                    form_26A: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.form_26A),
                    co_owned_prop: ITRDetails.co_owned_prop == 1 ? ITRDetails.co_owned_prop = true : ITRDetails.co_owned_prop = false,
                    co_owned_prop_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.co_owned_prop_image),
                    partner_firms: ITRDetails.partner_firms == 1 ? ITRDetails.partner_firms = true : ITRDetails.partner_firms = false,
                    partner_firm_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.partner_firm_image),
                    capital_gains: ITRDetails.capital_gains == 1 ? ITRDetails.capital_gains = true : ITRDetails.capital_gains = false,
                    capital_gain_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.capital_gain_image),
                    other_source_income: ITRDetails.other_source_income == 1 ? ITRDetails.other_source_income = true : ITRDetails.other_source_income = false,
                    othr_source_incm_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.othr_source_incm_image),
                    immovable_assets: ITRDetails.immovable_assets == 1 ? ITRDetails.immovable_assets = true : ITRDetails.immovable_assets = false,
                    immovable_asset_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.immovable_asset_image),
                    outside_income: ITRDetails.outside_income == 1 ? ITRDetails.outside_income = true : ITRDetails.outside_income = false,
                    outside_income_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.outside_income_image),
                    assets_partner: ITRDetails.assets_partner == 1 ? ITRDetails.assets_partner = true : ITRDetails.assets_partner = false,
                    assets_partner_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.assets_partner_image),
                    return_filing_date: ITRDetails.return_filing_date == "0000-00-00 00:00:00" ? '' : new Date(ITRDetails.return_filing_date),
                    loss_amnt: ITRDetails.loss_amnt == 0.00 ? '' : ITRDetails.loss_amnt,
                    other_cntry_tax: ITRDetails.other_cntry_tax == 1 ? ITRDetails.other_cntry_tax = true : ITRDetails.other_cntry_tax = false,
                    othr_cntry_tax_paid_img: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.othr_cntry_tax_paid_img),
                    jewellery_amnt: ITRDetails.jewellery_amnt == 0.00 ? '' : ITRDetails.jewellery_amnt,
                    paintings_amnt: ITRDetails.paintings_amnt == 0.00 ? '' : ITRDetails.paintings_amnt,
                    vehicles_amnt: ITRDetails.vehicles_amnt == 0.00 ? '' : ITRDetails.vehicles_amnt,
                    bank_amnt: ITRDetails.bank_amnt == 0.00 ? '' : ITRDetails.bank_amnt,
                    share_amnt: ITRDetails.share_amnt == 0.00 ? '' : ITRDetails.share_amnt,
                    loan_amnt: ITRDetails.loan_amnt == 0.00 ? '' : ITRDetails.loan_amnt,
                    cash_amnt: ITRDetails.cash_amnt == 0.00 ? '' : ITRDetails.cash_amnt,
                    other_liability_amnt: ITRDetails.other_liability_amnt == 0.00 ? '' : ITRDetails.other_liability_amnt,
                    complete_finance_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.complete_finance_image),
                    business_nature: ITRDetails.business_nature == "undefined" ? '' : ITRDetails.business_nature,
                    audit_report: ITRDetails.audit_report == 1 ? ITRDetails.audit_report = true : ITRDetails.audit_report = false,
                    audit_report_img: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.audit_report_img),
                    turnover: ITRDetails.turnover == 0.00 ? '' : ITRDetails.turnover,
                    net_profit: ITRDetails.net_profit == 0.00 ? '' : ITRDetails.net_profit
                }
            })
        }

        function saveITR1(data) {
            $dashboardModel.itr1details(data).then(function (result) {
                if (result.data.success) {
                    $scope.tabs.disable = false;
                    $timeout(function () {
                        $scope.nextTab();
                    });
                    $scope.itr.id = result.data.data;
                    if ($scope.itr.ITR == 2)
                        $scope.message = true;
                    toastr.success(result.data.message, 'Success!');
                }
                $appModel.progressBar(false, $scope);
            })
        }

        function saveITR2(data) {
            $dashboardModel.itr2details(data).then(function (result) {
                if (result.data.success) {
                    $scope.message = true;
                    toastr.success(result.data.message, 'Success!');
                }
                $appModel.progressBar(false, $scope);
            })
        }

        function saveITR3(data) {
            $dashboardModel.itr3details(data).then(function (result) {
                if (result.data.success) {
                    $scope.message = true;
                    toastr.success(result.data.message, 'Success!');
                }
                $appModel.progressBar(false, $scope);
            })
        }

        function saveITR4(data) {
            $dashboardModel.itr4details(data).then(function (result) {
                if (result.data.success) {
                    $scope.message = true;
                    toastr.success(result.data.message, 'Success!');
                }
                $appModel.progressBar(false, $scope);
            })
        }

        function getUserProfile() {
            if ($authModel.viewPermission($CONFIG.$ROLES.ADMIN)) {
                function fillITR(ITRDetails) {

                }
            }
            else {
                $dashboardModel.getUserProfile().then(function (result) {
                    if (result.data.success) {
                        var profile = result.data.data;
                        angular.extend($scope, {
                            user: {
                                name: profile.name,
                                email: profile.email,
                                mobile: profile.mobile,
                                residential_status: profile.residential_status,
                                employer_category: profile.employer_category,
                                ifsc_code: profile.ifsc_code == "null" ? '' : profile.ifsc_code,
                                address: profile.address == "null" ? '' : profile.address,
                                pan_image: $filter('parseImageCategory')(profile.images, $CONFIG.ITR_Image_Category.pan_image),
                                previous_return_image: $filter('parseImageCategory')(profile.images, $CONFIG.ITR_Image_Category.previous_return_image),
                                bank_statement: $filter('parseImageCategory')(profile.images, $CONFIG.ITR_Image_Category.bank_statement)
                            }
                        })
                    }
                });
            }
        }

        function postUserProfile(myFormData) {
            $dashboardModel.postUserProfile(myFormData).then(function (result) {
                if (result.data.success) {
                    $appModel.progressBar(false, $scope);
                    toastr.success(result.data.message, 'Success!');
                    $timeout(function () {
                        $scope.nextTab();
                    });
                }
            });
        }

        function deleteImage(data, item, obj) {
            $dashboardModel.deleteITRImage(data).then(function (result) {
                if (result.data.success) {
                    var index = $scope.itr[obj].indexOf(item);
                    $scope.itr[obj].splice(index, 1);
                }
            });
        }

        $scope.$watchCollection(function () {
            return $appModel.loadMasterCategory;
        }, function (newval, oldval) {
            if ($appModel.loadMasterCategory.length > 0) {
                var loadMaster = $filter('parseParent')($appModel.loadMasterCategory, 0);
                $scope.ITR = loadMaster.itr;
                $scope.residence = loadMaster.residential_status;
                $scope.employer = loadMaster.employer_category;
            }
        });
    }])
    .controller('corporateITRList', ['$scope', '$state', '$filter', 'resolveData', 'toastr', '$appModel', '$dashboardModel', 'localStorageService', function ($scope, $state, $filter, resolveData, toastr, $appModel, $dashboardModel, localStorageService) {
        if (!$dashboardModel.corpItrList.length > 0) {
            $scope.not_found = true;
            $scope.itr_exists = false;
            $scope.message = 'Data Not Found';
            return false;
        }
        $scope.itr_exists = true;
        angular.extend($scope, {
            filterdata: {
                minValue: 0,
                maxValue: 100000
            },
            status: {},
            itr: {selected: []},
            toggle: function (item, list) {
                toggleCheckbox(parseInt(item), list);
            },
            exists: function (item, list) {
                return list.indexOf(parseInt(item)) > -1;
            },
            editTabDialog: function (row) {
                $state.go('add-corp-itr', {id: row.id});
            }

        });

        $scope.$watchCollection(function () {
            return $dashboardModel.corpItrList;
        }, function (newval, oldval) {
            if ($dashboardModel.corpItrList.length > 0) {
                $scope.ITR = $dashboardModel.corpItrList;
            }
        });

    }])
    .controller('addCorporateITR', ['$scope', '$rootScope', '$state', '$stateParams', '$CONFIG', 'toastr', '$filter', '$API', '$timeout', '$appModel', '$authModel', '$dashboardModel', 'resolveData', function ($scope, $rootScope, $state, $stateParams, $CONFIG, toastr, $filter, $API, $timeout, $appModel, $authModel, $dashboardModel, resolveData) {
        angular.extend($scope, {
            company: {
                ITR: 20
            },
            tabs: {
                max: 2,
                selectedIndex: 0,
                disable: true
            },
            first: true,
            showBtns: false,
            lastFile: null,

            remove: function (item, obj) {
                if (confirm("Are You Sure?")) {
                    if (!item.itr_id) {
                        var index = $scope.company[obj].indexOf(item);
                        $scope.company[obj].splice(index, 1);
                    }
                    else {
                        var data = {
                            itr_id: item.itr_id,
                            category: item.category,
                            img_name: item.image
                        };
                        deleteImage(data, item, obj)
                    }
                }
            },
            corporateDetails: function () {
                if ($scope.DetailsForm.$valid) {
                    $appModel.progressBar(true, $scope);
                    var myFormData = new FormData();
                    angular.forEach($scope.company.pan_image, function (value, key) {
                        myFormData.append('pan_image[]', value._file);
                    });
                    angular.forEach($scope.company.adhar_image, function (value, key) {
                        myFormData.append('adhar_image[]', value._file);
                    });
                    angular.forEach($scope.company.bank_statement, function (value, key) {
                        myFormData.append('bank_statement[]', value._file);
                    });
                    myFormData.append('company_name', $scope.company.name);
                    myFormData.append('contact', $scope.company.contact);
                    myFormData.append('bank_name', $scope.company.bank_name);
                    myFormData.append('bank_ifsc', $scope.company.ifsc_code);
                    myFormData.append('company_pan', $scope.company.pan_no);
                    myFormData.append('company_adhar', $scope.company.adhar_no);
                    myFormData.append('company_add', $scope.company.address);
                    myFormData.append('id', $scope.company.id);
                    saveDetails(myFormData);
                }
            },

            submitITR5: function () {
                var myFormData = new FormData();
                angular.forEach($scope.company.audit_report_img, function (value, key) {
                    myFormData.append('audit_report_img[]', value._file);
                });
                angular.forEach($scope.company.previous_return_image, function (value, key) {
                    myFormData.append('previous_return_image[]', value._file);
                });
                angular.forEach($scope.company.form_26A, function (value, key) {
                    myFormData.append('form_26A[]', value._file);
                });
                angular.forEach($scope.company.financial_documents, function (value, key) {
                    myFormData.append('financial_documents[]', value._file);
                });
                angular.forEach($scope.company.income_computation, function (value, key) {
                    myFormData.append('income_computation[]', value._file);
                });
                angular.forEach($scope.company.share_holders, function (value, key) {
                    myFormData.append('share_holders[]', value._file);
                });
                myFormData.append('id', $scope.company.id);
                myFormData.append('ITR', $scope.company.ITR);
                saveITR5(myFormData);
            },

            nextTab: function () {
                $dashboardModel.nextTab($scope);
            },
            backTab: function () {
                $dashboardModel.backTab($scope);
            }
        });

        function saveDetails(data) {
            $dashboardModel.corporateDetails(data).then(function (result) {
                if (result.data.success) {
                    $scope.tabs.disable = false;
                    $timeout(function () {
                        $scope.nextTab();
                    });
                    $scope.company.id = result.data.data;
                    toastr.success(result.data.message, 'Success!');
                }
                $appModel.progressBar(false, $scope);
            })
        }

        function saveITR5(data) {
            $dashboardModel.itr5details(data).then(function (result) {
                if (result.data.success) {
                    $scope.message = true;
                    toastr.success(result.data.message, 'Success!');
                }
                $appModel.progressBar(false, $scope);
            })
        }

        function deleteImage(data, item, obj) {
            $dashboardModel.deleteCoprporateImage(data).then(function (result) {
                if (result.data.success) {
                    var index = $scope.company[obj].indexOf(item);
                    $scope.company[obj].splice(index, 1);
                }
            });
        }

        if (resolveData && resolveData.data.success) {
            var ITRDetails = resolveData.data.data;
            fillITR(ITRDetails);
            $scope.tabs.disable = false;
        }
        function fillITR(ITRDetails) {
            angular.extend($scope, {
                company: {
                    id: ITRDetails.id,
                    ITR: ITRDetails.ITR,
                    name: ITRDetails.company_name,
                    contact: ITRDetails.contact,
                    bank_name: ITRDetails.bank_name == "undefined" ? '' : ITRDetails.bank_name,
                    ifsc_code: ITRDetails.bank_ifsc == "undefined" ? '' : ITRDetails.bank_ifsc,
                    address: ITRDetails.company_add == "undefined" ? '' : ITRDetails.company_add,
                    pan_no: ITRDetails.company_pan == "undefined" ? '' : ITRDetails.company_pan,
                    adhar_no: ITRDetails.company_adhar == "undefined" ? '' : ITRDetails.company_adhar,
                    pan_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.pan_image),
                    adhar_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.adhar_image),
                    bank_statement: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.bank_statement),
                    audit_report_img: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.audit_report_img),
                    previous_return_image: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.previous_return_image),
                    form_26A: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.form_26A),
                    financial_documents: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.financial_documents),
                    income_computation: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.income_computation),
                    share_holders: $filter('parseImageCategory')(ITRDetails.itr_images, $CONFIG.ITR_Image_Category.share_holders),
                }
            })
        }

        $scope.$watchCollection(function () {
            return $appModel.loadMasterCategory;
        }, function (newval, oldval) {
            if ($appModel.loadMasterCategory.length > 0) {
                var loadMaster = $filter('parseParent')($appModel.loadMasterCategory, 0);
                $scope.ITR = loadMaster.itr;
            }
        });
    }])
    .controller('gstFilesCtrl', ['$scope', '$state', '$filter', 'resolveData', 'toastr', '$appModel', '$dashboardModel', 'localStorageService', function ($scope, $state, $filter, resolveData, toastr, $appModel, $dashboardModel, localStorageService) {
        localStorageService.clearAll();
        if (!$dashboardModel.GSTFiles.length > 0) {
            $scope.not_found = true;
            $scope.message = 'Data Not Found';
            return false;
        }
        angular.extend($scope, {
            filterdata: {
                minValue: 0,
                maxValue: 100000
            },
            status: {},
            itr: {selected: []},
            toggle: function (item, list) {
                toggleCheckbox(parseInt(item), list);
            },
            exists: function (item, list) {
                return list.indexOf(parseInt(item)) > -1;
            },
            editTabDialog: function (row) {
                $state.go('upload-gst', {id: row.agent_id});
            },
            sortby: function (item, order) {
                order = order ? 'desc' : 'asc';
                var data = angular.extend($scope.filterdata, {orderby: item, order: order});
                itrList(data, item, order);
            },

            changeStatus: function () {
                if ($scope.itr && $scope.itr.status > 0 && $scope.itr.selected.length > 0) {
                    var data = {
                        id: $scope.itr.selected,
                        status: $scope.itr.status
                    };
                    itrStatus(data);
                } else if (!$scope.itr.selected.length > 0 && $scope.itr) {
                    var status = {
                        status: $scope.itr.status
                    };
                    itrList(status);
                }
            }
        });
        /*  function itrList(data) {
         data = {
         status: data.status,
         orderBy: data.orderby,
         order: data.order
         };
         $dashboardModel.ITRList(data).then(function (result) {
         if (!result.data.data.length > 0) {
         $scope.no_item = true;
         $scope.hide_list = true;
         } else {
         $scope.hide_list = false;
         $scope.no_item = false;
         }
         })
         }

         $scope.$watchCollection(function () {
         return $dashboardModel.itrList;
         }, function (newval, oldval) {
         if ($dashboardModel.itrList.length > 0) {
         $scope.ITR = $dashboardModel.itrList;
         $scope.not_found = $scope.ITR.length > 0 ? false : true;
         $scope.directives = $scope.ITR.length > 0 ? true : false;
         }
         });

         function toggleCheckbox(item, list) {
         var idx = list.indexOf(item);
         if (idx > -1) {
         list.splice(idx, 1);
         } else {
         list.push(item);
         }
         }

         function itrStatus(data) {
         $dashboardModel.itrStatus(data).then(function (result) {
         if (result.data.success) {
         toastr.success("status Updated", 'Success!');
         }
         $appModel.progressBar(false, $scope);
         })
         }

         $scope.$watchCollection(function () {
         return $appModel.loadMasterCategory;
         }, function (newval, oldval) {
         if ($appModel.loadMasterCategory.length > 0) {
         var loadMaster = $filter('parseParent')($appModel.loadMasterCategory, 0);
         $scope.status = loadMaster.application_status;
         }
         });*/

        $scope.$watchCollection(function () {
            return $dashboardModel.GSTFiles;
        }, function (newval, oldval) {
            if ($dashboardModel.GSTFiles.length > 0) {
                $scope.GST = $dashboardModel.GSTFiles;
            }
        });
    }])
    .controller('uploadGSTCtrl', ['$scope', '$rootScope', '$state', '$stateParams', '$CONFIG', 'toastr', '$filter', '$API', '$timeout', '$appModel', '$authModel', '$dashboardModel', 'resolveData', function ($scope, $rootScope, $state, $stateParams, $CONFIG, toastr, $filter, $API, $timeout, $appModel, $authModel, $dashboardModel, resolveData) {
        angular.extend($scope, {
            company: {
                ITR: 20
            },
            tabs: {
                max: 2,
                selectedIndex: 0,
                disable: true
            },
            first: true,
            showBtns: false,
            lastFile: null,

            remove: function (item, obj) {
                if (confirm("Are You Sure?")) {
                    if (!item.agent_id) {
                        var index = $scope.gst[obj].indexOf(item);
                        $scope.gst[obj].splice(index, 1);
                    }
                    else {
                        var data = {
                            agent_id: item.agent_id,
                            category: item.category,
                            img_name: item.image
                        };
                        deleteImage(data, item, obj)
                    }
                }
            },
            uploadGST: function () {
                if ($scope.gst == undefined) {
                    confirm('please select a file')
                } else {
                    var myFormData = new FormData();
                    angular.forEach($scope.gst.gst_files, function (value, key) {
                        myFormData.append('gst_files[]', value._file);
                    });
                    uploadGST(myFormData);
                }
            },

            nextTab: function () {
                $dashboardModel.nextTab($scope);
            },
            backTab: function () {
                $dashboardModel.backTab($scope);
            }
        });

        function uploadGST(data) {
            $dashboardModel.uploadGST(data).then(function (result) {
                if (result.data.success) {
                    $scope.message = true;
                    toastr.success(result.data.message, 'Success!');
                }
                $appModel.progressBar(false, $scope);
            })
        }

        function deleteImage(data, item, obj) {
            $dashboardModel.deleteGSTImage(data).then(function (result) {
                if (result.data.success) {
                    var index = $scope.gst[obj].indexOf(item);
                    $scope.gst[obj].splice(index, 1);
                }
            });
        }

        if (resolveData && resolveData.data.success) {
            var GSTFiles = resolveData.data.data;
            fillGST(GSTFiles);
            $scope.tabs.disable = false;
        }
        function fillGST(GSTFiles) {
            angular.extend($scope, {
                gst: {
                    gst_files: GSTFiles.gst_images
                }
            })
        }

        $scope.$watchCollection(function () {
            return $appModel.loadMasterCategory;
        }, function (newval, oldval) {
            if ($appModel.loadMasterCategory.length > 0) {
                var loadMaster = $filter('parseParent')($appModel.loadMasterCategory, 0);
                $scope.ITR = loadMaster.itr;
            }
        });
    }])

