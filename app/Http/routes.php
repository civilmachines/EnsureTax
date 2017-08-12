<?php

$api = app('Dingo\Api\Routing\Router');
/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api\v1'], function ($api) {
        /* Method=POST
         * url:- api/v1/category
         * Get all property category
         */
//        $api->get('/category', function () {
//            return 'hello';
//        });

        /* -------------------------------ensureTax API----------------------------------------- */

        $api->get('/editITR/{id}', [
            'as' => 'post.editITR',
            'uses' => 'Auth\ITRController@editITR'
        ]);
        $api->get('/itrlist', [
            'as' => 'post.itrlist',
            'uses' => 'Auth\ITRController@ITRList'
        ]);
        $api->post('/itrstatus', [
            'as' => 'post.itrstatus',
            'uses' => 'Auth\ITRController@itrStatus'
        ]);
        $api->delete('/deleteimage', [
            'as' => 'deletedelimage',
            'uses' => 'Auth\ITRController@deleteImage'
        ]);
        $api->post('/postprofile', [
            'as' => 'post.profile',
            'uses' => 'Auth\ITRController@postProfile'
        ]);
        $api->get('/getprofile', [
            'as' => 'get.profile',
            'uses' => 'Auth\ITRController@getProfile'
        ]);
        $api->post('/password', [
            'as' => 'change.password',
            'uses' => 'Auth\ITRController@changePassword'
        ]);
        $api->get('/getcategory', [
            'as' => 'post.getcategory',
            'uses' => 'Auth\ITRController@loadCategory'
        ]);
        $api->post('/itr', [
            'as' => 'post.itr',
            'uses' => 'Auth\ITRController@selectITR'
        ]);
        $api->post('/itr1', [
            'as' => 'post.itr1',
            'uses' => 'Auth\ITRController@itr1'
        ]);
        $api->post('/itr2', [
            'as' => 'post.itr2',
            'uses' => 'Auth\ITRController@itr2'
        ]);
        $api->post('/itr3', [
            'as' => 'post.itr3',
            'uses' => 'Auth\ITRController@itr3'
        ]);
        $api->post('/itr4', [
            'as' => 'post.itr4',
            'uses' => 'Auth\ITRController@itr4'
        ]);
        $api->post('/contactus', [
            'as' => 'post.contactus',
            'uses' => 'Auth\ITRController@contactUS'
        ]);

        $api->post('/corporatedetails', [
            'as' => 'post.corporatedetails',
            'uses' => 'Auth\ITRController@corporateDetails'
        ]);
        $api->post('/itr5', [
            'as' => 'post.itr5',
            'uses' => 'Auth\ITRController@itr5'
        ]);
        $api->get('/corporateitrlist', [
            'as' => 'post.corporateitrlist',
            'uses' => 'Auth\ITRController@corporateitrlist'
        ]);
        $api->get('/editcorporateitr/{id}', [
            'as' => 'post.editcorporateitr',
            'uses' => 'Auth\ITRController@editCorporateITR'
        ]);
        $api->delete('/delcorpimg', [
            'as' => 'post.delcorpimg',
            'uses' => 'Auth\ITRController@delCorpImg'
        ]);

        $api->get('/gstfiles', [
            'as' => 'post.gstfiles',
            'uses' => 'Auth\ITRController@gstFiles'
        ]);
        $api->post('/uploadgst', [
            'as' => 'post.uploadgst',
            'uses' => 'Auth\ITRController@uploadGST'
        ]);

        $api->get('/editgstfile/{id}', [
            'as' => 'post.editgstfile',
            'uses' => 'Auth\ITRController@editGSTFile'
        ]);
        $api->delete('/deletegstimage', [
            'as' => 'post.deletegstimage',
            'uses' => 'Auth\ITRController@delGSTImg'
        ]);

        /* -------------------------------ensureTax API----------------------------------------- */

        $api->get('/category', [
            'as' => 'get.category',
            'uses' => 'PropertyController@categoryMaster'
        ]);
        /* Method=GET
         * url:- api/v1/state
         * GET All State
         */
        $api->get('/state', [
            'as' => 'states',
            'uses' => 'LocationController@getStates'
        ]);
        /* Method=GET
         * url:- api/v1/city
         * GET All City
         * query string =api/v1/city?state=state_id -- return state by city
         */
        $api->get('/city', [
            'as' => 'cities',
            'uses' => 'LocationController@getCities'
        ]);

        /* User Register
         * Method=Post
         * url:- api/v1/auth/register
         * Attribute= [name,email,username,password]
         * Required field= [name,email,username,password]
         * password mininmum 6 length
         * username must be 10 length mobile number
         */
        $api->post('/auth/register', [
            'as' => 'auth.register',
            'uses' => 'Auth\AuthController@Register'
        ]);
        /* User Login
         * Method=POST
         * url:- api/v1/auth/login
         * Attribute= [email,password]
         * Required =[email,password]
         */
        $api->post('/auth/login', [
            'as' => 'auth.login',
            'uses' => 'Auth\AuthController@Login'
        ]);

        /* Reset Password Request
         * Method=POST
         * url:- api/v1/auth/email
         * Attribute= [email]
         * Required =[email]
         */
        $api->post('/auth/reset', [
            'as' => 'reset.request',
            'uses' => 'Auth\AuthController@resetRequest'
        ]);
        /* Reset Password Request
         * Method=POST
         * url:- api/v1/auth/email
         * Attribute= [email]
         * Required =[email]
         */
        $api->post('/auth/reset/complete', [
            'as' => 'reset.request',
            'uses' => 'Auth\AuthController@resetComplete'
        ]);
        /* Email or Mobile No is Unique Check for Registration
         * Method=Post
         * url:- api/v1/auth/isunique
         * Attribute= [value,property]
         * value=abc@gmail or 9971815517
         * property=email or username
         */
        $api->post('/auth/isunique', [
            'as' => 'auth.checker',
            'uses' => 'Auth\AuthController@Unique'
        ]);
        /* Varify Link After Activation
         * Method=GET
         * url:- api/v1/auth/verify/{token}
         * Only for web after successful registration
         */
        $api->post('/auth/verify', [
            'as' => 'auth.verify',
            'uses' => 'Auth\AuthController@Activate'
        ]);
        /* Email or Mobile No is Exists Check for Login
         * Method=POST
         * url:- api/v1/auth/isexists
         * Attribute= [value,property]
         * value=abc@gmail or 9971815517
         * property=email or username
         */
        $api->post('/auth/isexists', [
            'as' => 'auth.isExist',
            'uses' => 'Auth\AuthController@isExists'
        ]);
        /* REEST PASSWORD REQUEST OTP VALIDATION
         * Method=POST
         * url:- api/v1/auth/otp
         * Attribute= [email.otp]
         * Required =[email]
         * only for web
         */
        $api->post('/auth/otp', [
            'as' => 'auth.checkotp',
            'uses' => 'Auth\AuthController@validateOTP'
        ]);

        $api->post('/auth/resent', [
            'as' => 'auth.resent',
            'uses' => 'Auth\AuthController@resentMail'
        ]);
        $api->post('auth/facebook', [
            'as' => 'auth.facebook',
            'uses' => 'Auth\AuthController@facebookLogin'
        ]);
        $api->post('auth/google', [
            'as' => 'auth.google',
            'uses' => 'Auth\AuthController@googleLogin'
        ]);

        /* Method =GET
         * url:- api/v1/user/property
         * Get User Property After Authentication
         */
        $api->group(['middleware' => ['jwt.auth']], function ($api) {

            /* Method=GET
             * url:- api/v1/profile
             * GET Profile after authentication
             */
            $api->post('/auth/user/logout', [
                'as' => 'auth.logout',
                'uses' => 'Auth\AuthController@logout'
            ]);
            $api->get('/auth/user/profile', [
                'as' => 'get.profile',
                'uses' => 'Auth\AuthController@getProfile'
            ]);
        });
    });
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::get('/dashboard/profile', function () {
        return view('dashboard');
    });
    Route::get('/dashboard/password', function () {
        return view('dashboard');
    });
    Route::get('/dashboard/itr/add/{id?}', function () {
        return view('dashboard');
    })->where('id', '[0-9]+');
    Route::get('/dashboard/itr', function () {
        return view('dashboard');
    });
    Route::get('/dashboard/corporate', function () {
        return view('dashboard');
    });
    Route::get('/dashboard/corporate/add/{id?}', function () {
        return view('dashboard');
    });
    Route::get('/dashboard/gstfiles', function () {
        return view('dashboard');
    });
    Route::get('/dashboard/gstfiles/upload/{id?}', function () {
        return view('dashboard');
    });

});
Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('static.about');
});
Route::get('/services', function () {
    return view('static.services');
});
Route::get('/career', function () {
    return view('static.career');
});
Route::get('/contact', function () {
    return view('static.contact');
});
Route::get('/faq', function () {
    return view('static.faq');
});
Route::get('/terms', function () {
    return view('static.terms');
});
