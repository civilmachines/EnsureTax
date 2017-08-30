<?php

namespace App\Models\User;


use DateTime;
use Config;
use JWTAuth;
use Auth;
use Carbon\Carbon;
use App\Helpers\LeaseHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Traits\UserTrait;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable,
        Authorizable,
        CanResetPassword,
        UserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    public function agents()
    {
        return $this->hasOne('App\Models\User\UserProfile', 'user_id');
    }

    public function socialUser()
    {
        return $this->hasMany('App\Models\User\SocialUser', 'user_id');
    }

    protected $table = 'users';
    protected $fillable = ['name', 'username', 'email', 'password', 'block', 'sendEmail', 'registerDate',
        'lastvisitDate', 'activation', 'lastResetTime', 'resetCount', 'otpKey', 'otep', 'requireReset'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'password', 'block', 'sendEmail', 'agents', 'registerDate',
        'lastvisitDate', 'activation', 'lastResetTime', 'resetCount', 'otpKey', 'otep', 'requireReset', 'params'];
    public $timestamps = false;

    public function getRememberToken()
    {
        return null; // not supported
    }

    public function setRememberToken($value)
    {
        // not supported
    }

    public function getRememberTokenName()
    {
        return null; // not supported
    }

    /**
     * Overrides the method to ignore the remember token.
     */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute) {
            parent::setAttribute($key, $value);
        }
    }

    /**
     * Method to get the registration form data.
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public static function store($data, $provider = FALSE, $owner = FALSE)
    {
        $response = array();
        if ($provider) {
            $data['password'] = bcrypt($data['id'] . '_' . $data['provider']);
            $data['block'] = 0;
            $data['activation'] = '';
        } else {
            if (array_has($data, 'password')) {
                $data['password'] = bcrypt($data['password']);
            }
            if ($owner) {
                $data['block'] = 0;
                $data['activation'] = '';
            } else {
                $data['activation'] = LeaseHelper::getHash(LeaseHelper::getRandomPassword());
                $data['block'] = 1;
            }
        }
        $data['sendEmail'] = 0;
        $data['registerDate'] = new DateTime();
        try {
            $user = User::create($data);
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, 'Please contact administrator! failed to create new user', $ex->getMessage());
        }
        try {
            $userGroup = UserGroup::where('title', 'Registered')->select('id')->first();
        } catch (ModelNotFoundException $ex) {
            return LeaseHelper::response(false, 500, 'Please contact administrator! user registered but failed to create new user group', $ex->getMessage());
        }
        if ($userGroup) {
            UserGroupMap::create(['user_id' => $user->id, 'group_id' => $userGroup->id]);
        }
        $result = self::createOTP($user);
        if (!$result['success']) {
            return $result;
        }
        $response['user'] = $user;
        $response['otp'] = $result['data']['otp'];
        return LeaseHelper::response(true, 200, trans('auth.messages.activation_link'), $response);
    }

    /**
     * Method to activate a user account.
     *
     * @param   string $token The activation token.
     *
     * @return  mixed False on failure, user object on success.
     */
    public static function activate($request)
    {
        $response = array();
        $field = LeaseHelper::parseMobileOrEmail($request);
        $request->merge([$field => $request->input('email')]);
        try {
            $user = User::where($field, $request->input('email'))->firstorfail();
            $result = self::validateOTP($request, $user);
            if (!$result['success']) {
                return $result;
            }
            $user->activation = null;
            $user->resetCount = 0;
            $user->otep = '';
            $user->block = 0;
            $user->save();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, 'Unauthorised Access! Verification code not found.',  $ex->getMessage());
        }
        return self::login($request);
    }

    public static function login($request)
    {
        $field = LeaseHelper::parseMobileOrEmail($request);
        $request->merge([$field => $request->input('email')]);
        try {
            $user = User::where($field, $request->input('email'))->firstorfail();
        } catch (ModelNotFoundException $ex) {
            return LeaseHelper::response(false, 500, 'Login Denied', $ex->getMessage());
        }
//        $checkResponse = self::checkBlocked($user);
//        if (!$checkResponse['success'])
//            return $checkResponse;
        try {
// attempt to verify the credentials and create a token for the user
//    for token generation JWTAuth::attempt($data)
//            Auth::attempt($request->only('email', 'password'))
            if (!$request->has('password'))
                Auth::login($user);
            else
                if (!Auth::attempt($request->only($field, 'password')))
                    return LeaseHelper::response(false, 200, trans('auth.messages.login_denied'));

        } catch (JWTException $ex) {
// something went wrong whilst attempting to encode the token
            return LeaseHelper::response(false, 500, 'Login Denied', $ex->getMessage());
        }
        try {
            UserProfile::updateProfile($user, Config::get('auth.roles.Registered'));
            $user->lastvisitDate = new DateTime();
            $user->save();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, $ex->getMessage());
        }
//        $user->agent = $user->hasRole(['Registered', 'Agent'], true);
        $token = LeaseHelper::createToken($user);
        if (!$token) {
            return LeaseHelper::response(false, 200, trans('auth.messages.role_denied'));
        }
        return LeaseHelper::response(true, 200, 'Login successful', compact('token'));
    }

    /**
     * Method to check blocked user account.
     *
     * @return \Illuminate\Http\Response
     */
    public static function checkBlocked($user)
    {
        $response = array();
//        $condition = ['email' => $data['email'], 'block' => 0, 'activation' => ''];

        if (!empty($user->activation)) {
//            if (array_has($data, 'otp') && $data['otp'] != null) {
//                $result = self::activateLogin($data);
//                if (!$result['success']) {
//                    return $result;
//                }
//                $user = User::where('email', $data['email'])->firstorfail();
//            } else {
            $response['activated'] = FALSE;
            return LeaseHelper::response(false, 200, trans('auth.messages.activation'), $response['activated']);
            //            }
        }
        if ($user->block == 1) {
            return LeaseHelper::response(false, 200, trans('auth.messages.blocked'), $response['activated']);
        }
        return LeaseHelper::response(true, 200, 'successful');
    }

    /**
     * Method to Reset Request.
     *
     * @return \Illuminate\Http\Response
     */
    public static function processResetRequest($request)
    {
        $response = array();
        $field = LeaseHelper::parseMobileOrEmail($request);
        $request->merge([$field => $request->input('email')]);
        $user = User::where($field, $request->input('email'))->first();
        if (!$user) {
            return LeaseHelper::response(false, 200, trans('auth.messages.user'));
        }
        if ($user->block == 1) {
            return LeaseHelper::response(false, 200, trans('auth.messages.blocked'), $response['activated']);
        }
        return self::createOTP($user);
    }

    private static function checkResetLimit($user)
    {
        $maxCount = Config::get('app.reset_count');
        $resetHours = Config::get('app.reset_time');
        $result = true;
        $datetime = Carbon::now();
        $lastResetTime = Carbon::parse($user->lastResetTime);
        $hoursSinceLastReset = $lastResetTime->diffInHours(Carbon::now());
        if ($hoursSinceLastReset > $resetHours) {
// If it's been long enough, start a new reset count
            $user->lastResetTime = $datetime;
            $user->resetCount = 1;
        } elseif ($user->resetCount < $maxCount) {
            $user->lastResetTime = $datetime;
// If we are under the max count, just increment the counter
            ++$user->resetCount;
        } else {
// At this point, we know we have exceeded the maximum resets for the time period
            $result = false;
        }
        return $result;
    }

    /**
     * Method to Reset Request Complete.
     *
     * @return \Illuminate\Http\Response
     */
    public static function processResetComplete($request)
    {
        $response = array();
        $field = LeaseHelper::parseMobileOrEmail($request);
        $request->merge([$field => $request->input('email')]);
        try {
            $user = User::where($field, $request->input('email'))
                ->where('resetCount', '>', 0)
                ->firstorfail();
        } catch (ModelNotFoundException $ex) {
            return LeaseHelper::response(false, 500, $ex->getMessage());
        }
        $result = self::validateOTP($request, $user);
        if (!$result['success']) {
            return $result;
        }
        $user->password = bcrypt($request->input('password'));
        $user->otep = '';
        $user->resetCount = 0;
        try {
            $user->save();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, $ex->getMessage());
        }
        return self::login($request);
    }

    public static function changePassword($data)
    {
        $response = array();
        try {
            $user = Auth::User();
//            if (!Hash::check($data['password'], $user->password)) {
//                return LeaseHelper::response(false, 200, 'Please enter valid current password');
//            }
            $user->password = bcrypt($data['new_password']);
            $user->save();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, "Failed to change password", $ex->getMessage());
        }
        return LeaseHelper::response(TRUE, 200, "Password changed Successfully!");
    }

    public static function validateOTP($request, $user = null)
    {
        if ($user == null) {
            $field = LeaseHelper::parseMobileOrEmail($request);
            $request->merge([$field => $request->input('email')]);
            try {
                $user = User::where($field, $request->input('email'))->firstorfail();
            } catch (Exception $ex) {
                return LeaseHelper::response(false, 500, 'Email id does not match', $ex->getMessage());
            }
        }
        $resetHours = Config::get('app.reset_time');
        $lastResetTime = Carbon::parse($user->lastResetTime);
        $hoursSinceLastReset = $lastResetTime->diffInHours(Carbon::now());
        if ($hoursSinceLastReset > $resetHours) {
            return LeaseHelper::response(false, 200, "Whoops! OTP has been expired.");
        }
        if (!Hash::check($request->input('otp'), $user->otep)) {
            return LeaseHelper::response(false, 200, "Whoops! OTP is not valid.");
        }
        return LeaseHelper::response(true, 200, "successful");
    }

    public static function resentVarification($data)
    {
        $response = array();
        try {
            $user = User::where('email', $data['email'])->firstorfail();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, 'email id does not match', $ex->getMessage());
        }
        $user->activation = LeaseHelper::getHash(LeaseHelper::getRandomPassword());
        $user->block = 1;
        $user->sendEmail = $user->sendEmail + 1;
        $user->lastvisitDate = null;
        try {
            $user->save();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, $ex->getMessage());
        }
        $response['user'] = $user;
        $response['activation_link'] = version('v1')->route('auth.verify', $user->activation);
        return LeaseHelper::response(true, 200, "You new verification link has been sent to your registered email address!", $response);
    }

//    public static function authenticateCheckout($request)
//    {
//        $response = array();
//        if (!User::where('email', $request->input('email'))->exists()) {
//            $result = self::store($request->only('email'));
//            if (!$result['success']) {
//                return $result;
//            }
//        }
//        try {
//            $user = User::where('email', $request->input('email'))->firstorfail();
//        } catch (Exception $ex) {
//            $response['success'] = FALSE;
//            $response['status_code'] = 500;
//            $response['errors'] = $ex->getMessage();
//            $response['message'] = trans('auth.messages.user');
//            return $response;
//        }
//        if (empty($user->password) && empty($user->username)) {
//            $result = self:: createOTP($request->only('email'));
//            if (!$result['success']) {
//                return $result;
//            }
//            $response['success'] = true;
//            $response['status_code'] = 200;
//            $response['user_found'] = false;
//            $response['otp'] = $result['otp'];
//            $response['email'] = $user->email;
//            $response['message'] = 'user not found';
//            return $response;
//        }
//        $result = self::checkBlocked($request->only('email'));
//        if (!$result['success']) {
//            if (!$result['activated']) {
//                $result = self:: createOTP($request->only('email'));
//                if (!$result['success']) {
//                    return $result;
//                }
//                $response['success'] = true;
//                $response['status_code'] = 200;
//                $response['user_found'] = true;
//                $response['otp'] = $result['otp'];
//                $response['email'] = $user->email;
//                $response['activated'] = false;
//                $response['message'] = 'Your account has not been activated yet!';
//                return $response;
//            }
//            return $result;
//        }
//        $response['success'] = true;
//        $response['status_code'] = 200;
//        $response['user_found'] = true;
//        $response['message'] = 'user found';
//        return $response;
//    }

    public static function verifyLogin($request)
    {
        $response = array();
        try {
            $user = User::where('email', $request->input('email'))->firstorfail();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, 'email id does not match', $ex->getMessage());
        }
        if (!Hash::check($request->input('otp'), $user->otep)) {
            return LeaseHelper::response(false, 200, "Whoops! The varification code is not valid.");
        }
        $user->fill($request->all());
        $user->username = $request->input('contact');
        $user->password = bcrypt($request->input('password'));
        $user->activation = null;
        $user->resetCount = 0;
        $user->otep = '';
        $user->block = 0;
        try {
            $user->save();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, 'Please contact administrator! failed to create new user', $ex->getMessage());
        }
        return LeaseHelper::response(true, 200, 'successful');
    }

    public static function createOTP($user)
    {
        if (!self::checkResetLimit($user)) {
            return LeaseHelper::response(false, 200, trans('auth.messages.reset_limit', ['time' => Config::get('app.reset_time')]));
        }
        $otp = rand(100000, 999999);
        $user->otep = bcrypt($otp);
        try {
            $user->save();
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 'Failed to verify! please contact administrator', 500, $ex->getMessage());
        }
        $response['otp'] = $otp;
        $response['user'] = $user;
        return LeaseHelper::response(true, 200, 'Successful! OTP has been send', $response);
    }
}
