<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\LeaseHelper;
use App\Models\User\SocialUser;
use Config;
use JWTAuth;
use Mail;
use Auth;
use Validator;
use Socialite;
use Illuminate\Http\Request;
use App\Models\User\User;
use App\Models\User\UserProfile;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Requests\ResetComplete;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangePasswordRequest;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function Login(LoginRequest $request)
    {
        $result = User::login($request);
        return response($result, $result['status_code']);
    }
    /**
     * Register New User.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function Register(RegisterRequest $request)
    {
        $result = User::store($request->all());
        if (!$result['success']) {
            return response($result, $result['status_code']);
            // $this->dispatch(new UserRegisterMail($result['user'], $result['activation_link']));
            // $this->dispatch(new AdminRegisterMail($result['user']));
            // Artisan::call('queue:work');
        }
//        $response = $result['data'];
//        $data['message'] = $response['otp'] . ' is your ensureTax Verification code';
//        $data['contact'] = $response['user']->username;
//        LeaseHelper::sentSMS($data);
//        Mail::send('mailers.verification', ['user' => $response['user'], 'otp' => $response['otp']], function ($m) use ($response) {
//            $m->from(env('MAIL_USERNAME'), 'ensureTax');
//            $m->to($response['user']->email, $response['user']->name)->subject('ensuretax.com- Activation Code');
//        });
        return response(array_except($result, ['data']), $result['status_code']);
        // 'token' => JWTAuth::fromUser($user)
    }


    /**
     * Password Reset Request.
     * Post Method
     * @return \Illuminate\Http\Response
     */
    public function resetRequest(VerifyRequest $request)
    {
        $result = User::processResetRequest($request);
        if (!$result['success']) {
            return response($result, $result['status_code']);
//            $this->dispatch(new ResetPasswordMail($result['user'], $result['reset_link']));
        }
        $response = $result['data'];
        $field = LeaseHelper::parseMobileOrEmail($request);
        if ($field == 'email') {
            Mail::send('mailers.reset_password', ['user' => $response['user'], 'otp' => $response['otp']], function ($m) use ($response) {
                $m->from(env('MAIL_USERNAME'), 'ensureTax');
                $m->to($response['user']->email, $response['user']->name)->subject('ensuretax.com- Reset Password');
            });
        }
        if ($field == 'username') {
            $data['message'] = $response['otp'] . ' is your ensureTax password reset code';
            $data['contact'] = $response['user']->username;
            LeaseHelper:: sentSMS($data);
        }
        return response(array_except($result, ['data']), $result['status_code']);
    }

    /**
     * Password Reset Complete
     * Post Method
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resetComplete(ResetComplete $request)
    {
        $result = User::processResetComplete($request);
        if (!$result['success']) {
            return response($result, $result['status_code']);
        }
        $result = User::login($request);
        return response($result, $result['status_code']);
    }

    protected function Activate(VerifyRequest $request)
    {
        $result = User::activate($request);
        return response($result, $result['status_code']);
    }

    protected function Unique(Request $request)
    {
        $column = $request->only('property');
        $value = $request->only('value');
        if (is_numeric($request->input('value'))) {
            $column['property'] = 'username';
        } elseif (filter_var($request->input('value'), FILTER_VALIDATE_EMAIL)) {
            $column['property'] = 'email';
        }
        $user = User::where($column['property'], '=', $value['value'])->exists();
//        $result = (count($user) == 0);
        return response()->json(array(
            'error' => false,
            'isUnique' => $user,
            'status_code' => 200
        ), 200);
    }

    public function validateOTP(Request $request)
    {
        $result = User::validateOTP($request);
        return response($result, $result['status_code']);
    }


    public function resentMail(Request $request)
    {
        $field = LeaseHelper::parseMobileOrEmail($request);
        $request->merge([$field => $request->input('email')]);
        $user = User::where($field, $request->input('email'))->first();
        if (!$user) {
            return LeaseHelper::response(false, 200, trans('auth.messages.user'));
        }
        $result = User::createOTP($user);
        if (!$result['success']) {
            return response($result, $result['status_code']);
//            $this->dispatch(new ResetPasswordMail($result['user'], $result['reset_link']));
        }
        $response = $result['data'];
        if ($field == 'email') {
            Mail::send('mailers.verification', ['user' => $response['user'], 'otp' => $response['otp']], function ($m) use ($response) {
                $m->from(env('MAIL_USERNAME'), 'EnsureTax');
                $m->to($response['user']->email, $response['user']->name)->subject('ensuretax.com- Activation Code');
            });
        }
        if ($field == 'username') {
            $data['message'] = $response['otp'] . ' is your ensureTax Verification code';
            $data['contact'] = $response['user']->username;
            LeaseHelper:: sentSMS($data);
        }
        $result['message'] = trans('auth.messages.activation_link');
        return response(array_except($result, ['data']), $result['status_code']);

    }

    public function getProfile()
    {
        $result = UserProfile::getUserProfile();
        return response($result, $result['status_code']);
    }

    public function postProfile(ProfileRequest $request)
    {
        $user = Auth::User();
        $user->name = $request->input('name');
        if (!$user->save()) {
            $result = LeaseHelper::response(false, 500, 'failed to update user');
            return response($result, $result['status_code']);
        }
        $userProfile = $user->agents;
        $userProfile->name = $user->name;
        $userProfile->phone = $request->input('phone');
        $userProfile->address = $request->input('address');;
        try {
            $userProfile->save();
        } catch (Exception $exe) {
            $result = LeaseHelper::response(false, 500, $exe->getMessage());
            return response($result, $result['status_code']);
        }
        $result = LeaseHelper::response(true, 200, "Profile updated successfully");
        return response($result, $result['status_code']);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $credentials = $request->only('new_password');
        $result = User::changePassword($credentials);
        return response()->json($result, $result['status_code']);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->validate($request, ['token' => 'required']);
        try {
            Auth::logout();
            JWTAuth::invalidate($request->input('token'));
        } catch (Exception $exe) {
            $result = LeaseHelper::response(false, 500, $exe->getMessage());
            return response($result, $result['status_code']);
        }
        $result = LeaseHelper::response(true, 200, "successfull");
        return response($result, $result['status_code']);
    }

//    public function facebookLogin(Request $request)
//    {
//        $user = Socialite::driver('facebook')->userFromToken($request->input('access_token'));
//    }


    public function facebookLogin(Request $request)
    {
        $socialData = $request->all();
        $socialUser = Socialite::driver('facebook')->stateless()->user();
        $user = $socialUser->user;
        $user['accessToken'] = $socialUser->token;
        return $this->socialLogin($user, 'facebook');
    }

    public function googleLogin(Request $request)
    {
        $socialData = $request->all();
        $socialUser = Socialite::driver('google')->stateless()->user();
        $user = $socialUser->user;
        $user['accessToken'] = $socialUser->token;
        $user['name'] = $socialUser->name;
        $user['email'] = $socialUser->email;
        $user['avatar'] = $socialUser->avatar_original;
        return $this->socialLogin($user, 'google');
    }

    private function socialLogin($userData, $provider)
    {
        try {
            $result = SocialUser::sLogin($userData, $provider);
            if (!$result['success']) {
                return $result;
            }
            $user = $result['data'];
            if ($provider == 'google') {
                $user->image = $userData['avatar'];
            }
            if ($provider == 'facebook') {
                $user->image = 'http://graph.facebook.com/' . $userData['id'] . '/picture?type=normal';
            }
            $result = UserProfile::updateProfile($user, Config::get('global.user_profile.type.user'));
            if (!$result['success']) {
                return $result;
            }

            Auth::login($user);
            $token = LeaseHelper::createToken($user);
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, $ex->getMessage());
        }
        return LeaseHelper::response(true, 200, "Authenticated", compact('token'));
    }
}
