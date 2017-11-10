/**
 * Created by Admin on 4/6/2017.
 */
var app = angular.module('101housing', ['ui.router', 'ngMaterial', 'ngAnimate', 'ngMessages', 'ui.bootstrap', 'ui.bootstrap-paging', 'toastr', 'defaultTemplates', 'satellizer', 'ab-base64', 'LocalStorageModule'], ['$interpolateProvider', function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
}])
    .constant('$CONFIG', {
        'NOT_AVAIL_IMAGE': 'images/not_found.jpg',
        'IMAGE_URL': 'images/ensuretax/images/',
        'CORPORATE_IMAGE_URL': 'images/ensuretax/corporate/',
        'GST_IMAGE_URL': 'images/ensuretax/GST_Files/',
        'PDF_DEFAULT_IMAGE': 'images/pdf.png',
        'PRO_IMAGE_URL': 'images/ensuretax/profile/',
        'PROPERTY_NOT_FOUND': "<div ng-cloak data-ng-if='!total' class='col-md-12 not-found row-space-2 text-center'><figure><img src='images/not-founds.png' alt='Property not found'/></figure><h3>Didn't find property in this loacality !!</h3><p>We'll keep you posted when more properties come up.</p></div>",
        '$ROLES': {
            'REGISTERED': 'registered',
            'ADMIN': 'admin'
        },
        'ITR_Image_Category': {
            'form_16': 1,
            'loan_certificate': 2,
            'interest_certificate': 3,
            'claim_80C': 4,
            'claim_80D': 5,
            'claim_80G': 6,
            'other_deduction': 7,
            'tax_challan': 8,
            'form_26A': 9,
            'co_owned_prop_image': 10,
            'partner_firm_image': 11,
            'capital_gain_image': 12,
            'othr_source_incm_image': 13,
            'immovable_asset_image': 14,
            'outside_income_image': 15,
            'assets_partner_image': 16,
            'othr_cntry_tax_paid_img': 17,
            'complete_finance_image': 18,
            'audit_report_img': 19,
            'pan_image': 20,
            'previous_return_image': 21,
            'bank_statement': 22,
            'adhar_image': 23,
            'financial_documents': 24,
            'income_computation': 25,
            'share_holders': 26,
            'gst_files': 26,
        }
    })
    .constant('$API', {
        /*
         * LOGIN REGISTRATION API
         * */
        'API_POST_CHECK_UNIQUE': baseUrl + 'api/v1/auth/isunique',
        'API_POST_REGISTER': baseUrl + 'api/v1/auth/register',
        'API_POST_LOGIN': baseUrl + 'api/v1/auth/login',
        'API_POST_VERIFY': baseUrl + 'api/v1/auth/verify',

        'API_POST_SOCIAL_LOGIN': baseUrl + 'api/v1/auth/social',
        'API_POST_FACEBOOK_LOGIN': baseUrl + 'api/v1/auth/facebook',
        'API_POST_GOOGLE_LOGIN': baseUrl + 'api/v1/auth/google',
        'API_POST_RESET_REQUEST': baseUrl + 'api/v1/auth/reset',
        'API_POST_CHECK_OTP': baseUrl + 'api/v1/auth/otp',
        'API_POST_RESET_COMPLETE': baseUrl + 'api/v1/auth/reset/complete',
        'API_POST_RESEND': baseUrl + 'api/v1/auth/resent',
        'API_POST_AUTH_CHECKOUT': baseUrl + 'api/v1/auth/checkout',
        // 'API_POST_USER_PROFILE': baseUrl + 'api/v1/auth/user/profile',

        /*-------------------------------GST API-----------------------------------------*/
        'API_GST_USER_LOGIN': 'http://apigst.ensuretax.com/' + 'api/user/login/',
        'API_LOGIN_OTP_USER': 'http://apigst.ensuretax.com/' + 'api/user/loginotp/',

        /*-------------------------------ensureTax API-----------------------------------------*/

        'API_ITR_LIST': baseUrl + 'api/v1/itrlist',
        'API_GET_EDIT_ITR': baseUrl + 'api/v1/editITR/',
        'API_POST_ITR_STATUS': baseUrl + 'api/v1/itrstatus',
        'API_DELET_IMAGE': baseUrl + 'api/v1/deleteimage',
        'API_AUTH_GET_ALL_CATEGORY': baseUrl + 'api/v1/getcategory',
        'API_POST_SELECT_ITR': baseUrl + 'api/v1/itr',
        'API_POST_ITR1_DETAILS': baseUrl + 'api/v1/itr1',
        'API_POST_ITR2_DETAILS': baseUrl + 'api/v1/itr2',
        'API_POST_ITR3_DETAILS': baseUrl + 'api/v1/itr3',
        'API_POST_ITR4_DETAILS': baseUrl + 'api/v1/itr4',
        'API_GET_USER_PROFILE': baseUrl + 'api/v1/getprofile',
        'API_POST_USER_PROFILE': baseUrl + 'api/v1/postprofile',
        'API_POST_CHANGE_PASSWORD': baseUrl + 'api/v1/password',
        'POST_CONTACT_US': baseUrl + 'api/v1/contactus',
        'API_POST_CORPORATE_DETAILS': baseUrl + 'api/v1/corporatedetails',
        'API_POST_ITR5_DETAILS': baseUrl + 'api/v1/itr5',
        'API_CORP_ITR_LIST': baseUrl + 'api/v1/corporateitrlist',
        'API_EDIT_CORPORATE_ITR': baseUrl + 'api/v1/editcorporateitr/',
        'API_CORP_ITR_STATUS': baseUrl + 'api/v1/corpitrstatus',
        'API_DELET_CORP_IMAGE': baseUrl + 'api/v1/delcorpimg',
        'API_GET_GST_FILES': baseUrl + 'api/v1/gstfiles',
        'API_UPLOAD_GST_FILES': baseUrl + 'api/v1/uploadgst',
        'API_EDIT_GST_FILE': baseUrl + 'api/v1/editgstfile/',
        'API_DELETE_GST_IMAGE': baseUrl + 'api/v1/deletegstimage',
        'API_POST_GST_STATUS': baseUrl + 'api/v1/gststatus',
        /*-------------------------------ensureTax API-----------------------------------------*/

        /*
         * Property API
         * GET METHOD
         * */
        // 'API_USER_PROFILE': baseUrl + 'api/v1/auth/user/profile',
        'API_USER_LOGOUT': baseUrl + 'api/v1/auth/user/logout',

        'POST_PROPERTY_IMAGE': baseUrl + 'auth/property/image',
    })
    .run(['$rootScope', '$state', '$stateParams', '$anchorScroll', '$window', '$appModel', '$authModel', '$uibModalStack', '$auth', function ($rootScope, $state, $stateParams, $anchorScroll, $window, $appModel, $authModel, $uibModalStack, $auth) {
        $rootScope.bodyClass = 'white_bg';
        $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            $anchorScroll();
            if (angular.isDefined(toState.authenticate)) {
                if ((toState.authenticate && !$auth.isAuthenticated()) || (!toState.authenticate && $auth.isAuthenticated())) {
                    $appModel.logout();
                    event.preventDefault();
                    return;
                }
                // if (!toState.authenticate && $auth.isAuthenticated()) {
                //     $appModel.logout();
                //     event.preventDefault();
                //     return;
                // }
            }
            var top = $uibModalStack.getTop();
            if (top) {
                $uibModalStack.dismiss(top.key);
            }
        });
        $rootScope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {
            $rootScope.previousState = fromState.name;
            $rootScope.currentState = toState.name;
            $rootScope.previousState_params = toParams;
            $rootScope.auth = $auth.isAuthenticated();
            // $rootScope.progressbar.complete();
            if ($rootScope.auth) {
                $authModel.setUser($auth.getPayload());
                $rootScope.authUser = $authModel.getUser();
            }
            if (angular.isDefined(toState.data)) {
                if (angular.isDefined(toState.data.navClass))
                    $rootScope.navClass = toState.data.navClass;
                if (angular.isDefined(toState.data.bodyClass))
                    $rootScope.bodyClass = toState.data.bodyClass;
            } else {
                $rootScope.navClass = '';
                $rootScope.bodyClass = 'white_bg';
            }

        });
    }])
    .config(['$authProvider', '$API', 'toastrConfig', '$mdDateLocaleProvider', function ($authProvider, $API, toastrConfig, $mdDateLocaleProvider) {
        $authProvider.loginUrl = $API.API_POST_LOGIN;
        $authProvider.signupUrl = $API.API_POST_REGISTER;

        $authProvider.httpInterceptor = function () {
            return true;
        };
        $authProvider.withCredentials = false;
        $authProvider.tokenRoot = null;
        $authProvider.baseUrl = '/';
        $authProvider.tokenName = 'tax';
        $authProvider.tokenPrefix = 'en';
        $authProvider.tokenPrefix = 'satellizer';
        $authProvider.tokenName = '_s_t_';
        $authProvider.tokenHeader = 'Authorization';
        $authProvider.tokenType = 'Bearer';
        $authProvider.storageType = 'localStorage';
        $authProvider.facebook({
            clientId: '198523190660678',
            // responseType: 'token',
            url: $API.API_POST_FACEBOOK_LOGIN
        });
        $authProvider.google({
            clientId: '783139877765-p45og29bv58uh1k4kfpmg43dbshv4j8q.apps.googleusercontent.com',
            url: $API.API_POST_GOOGLE_LOGIN
        });
        angular.extend(toastrConfig, {
            autoDismiss: true,
            // containerId: 'toast-container',
            // maxOpened: 0,
            newestOnTop: true,
            positionClass: 'toast-top-center',
            // preventDuplicates: false,
            // preventOpenDuplicates: false,
            target: 'body'
        });
        $mdDateLocaleProvider.formatDate = function (date) {
            return moment(date).local().format('DD/MM/YYYY');
        };
    }])
    .provider('showErrorsConfig', function () {
        var _showSuccess;
        _showSuccess = false;
        this.showSuccess = function (showSuccess) {
            return _showSuccess = showSuccess;
        };
        this.$get = function () {
            return {showSuccess: _showSuccess};
        };
    });


// function skipIfLoggedIn($q, $auth) {
//     var deferred = $q.defer();
//     if ($auth.isAuthenticated()) {
//         deferred.reject();
//     } else {
//         deferred.resolve();
//     }
//     return deferred.promise;
// }
//
// function checkAuth($q, $window, $auth) {
//     console.log($auth.isAuthenticated());
//     var deferred = $q.defer();
//     if ($auth.isAuthenticated()) {
//         deferred.resolve();
//     } else {
//         $window.location.href = '/';
//     }
//     return deferred.promise;
// }