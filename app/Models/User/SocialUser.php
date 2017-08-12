<?php

namespace App\Models\User;

use App\Helpers\LeaseHelper;
use App\Models\User\User;
use App\Models\User\UserGroupMap;
use Illuminate\Database\Eloquent\Model;

class SocialUser extends Model
{

    protected $table = 'slogin_users';
    protected $fillable = [
        'user_id', 'slogin_id', 'provider', 'access_token'];
    public $timestamps = false;

    public static function sLogin($sUser, $provider)
    {
        $response = array();
        $user = User::where(['email' => $sUser['email']])->first();
        if (!$user) {
            $sUser['provider'] = $provider;
            $result = User::store($sUser, TRUE);
            $user = $result['user'];
        }
        $socialUser = SocialUser::where(['user_id' => $user->id, 'slogin_id' => $sUser['id'], 'provider' => $provider])->first();
        if (!$socialUser) {
            $socialUser = new SocialUser();
        }
        $socialUser->user_id = $user->id;
        $socialUser->slogin_id = $sUser['id'];
        $socialUser->provider = $provider;
        $socialUser->access_token = $sUser['accessToken'];
        try {
            $socialUser->save();
        } catch (Exception $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        if (UserGroupMap::where(['user_id' => $user->id, 'group_id' => 13])->exists()) {
            $user->agent = true;
        }
        return LeaseHelper::response(true, 200, "Authenticated", $user);

    }

}
