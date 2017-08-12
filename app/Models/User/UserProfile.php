<?php

namespace App\Models\User;

use App\Helpers\LeaseHelper;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Config;
use Auth;

class UserProfile extends Model
{

    protected $table = 'osrs_agents';
    protected $fillable = [
        'agent_type', 'name', 'alias', 'user_id', 'company_id', 'email', 'mobile', 'phone',
        'fax', 'address', 'city', 'state', 'country', 'photo', 'yahoo', 'skype', 'aim',
        'msn', 'gtalk', 'facebook', 'license', 'ordering', 'published', 'featured', 'request_to_approval', 'bio',
    ];
    protected $hidden = [
        'alias', 'user_id', 'company_id', 'fax', 'city', 'state', 'country', 'photo', 'yahoo', 'skype', 'aim',
        'msn', 'gtalk', 'facebook', 'license', 'ordering', 'published', 'featured', 'request_to_approval', 'bio',
    ];
    public $timestamps = false;

    public function images()
    {
        return $this->hasMany('App\Models\TaxItr\BasicDetailsImages', 'agent_id', 'id')->select('category', 'image', 'agent_id');
    }
    public function gstImages()
    {
        return $this->hasMany('App\Models\TaxItr\GSTFilesImages', 'agent_id', 'id');
    }


    public function scopeFilterByRole($query, $user)
    {
        /*  if ($user->hasRole('administrator')) {
              return;
          }*/
        $agent = $user->agents;
        if ($agent->agent_type == Config::get('global.user_profile.type.agent')) {
            $company = $agent->company;
            if ($company) {
                //                if ($company->user_id == $user->id) {
                $query->where('company_id', $company->id);
                //                } else {
                //                    $companyAdmin = $company->companyAdmin;
                //                    $query->whereIn('agent_id', [$agent->id, $companyAdmin->id]);
                //                }
            } else {
                $query->where('agent_id', $agent->id);
            }
        } else {
            $query->where('agent_id', $agent->id);
        }
    }


    public function company()
    {
        return $this->belongsTo('App\Models\User\Company', 'company_id');
    }

    public static function getUserProfile()
    {
        $user = Auth::User();
//        $token = JWTAuth::getToken();
//        if ($token) {
//            $token = JWTAuth::refresh($token);
//        }
        try {
            $userProfile = $user->agents()->with('images')->first();
        } catch (Exception $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(TRUE, 200, "User Profile Successful", $userProfile);
    }

    public static function updateProfile($user, $type, $company = 0)
    {
        $response = array();
        $data = array();
        $userProfile = $user->agents;
        if (!$userProfile) {
            $userProfile = new UserProfile();
        }
        $userProfile->name = $user->name;
        $userProfile->user_id = $user->id;
        $userProfile->email = $user->email;
        $userProfile->mobile = $user->username;
        $userProfile->agent_type = $userProfile->agent_type > 0 ? $userProfile->agent_type : $type;
        if ($company > 0) {
            $userProfile->company_id = $userProfile->company_id > 0 ? $userProfile->company_id : $company;
        } else {
//            $userProfile->agent_type = $userProfile->agent_type > 0 ? $userProfile->agent_type : Config::get('global.user_profile.type.user');
            $userProfile->company_id = $userProfile->company_id > 0 ? $userProfile->company_id : Config::get('global.user_profile.default_company');
        }
        $userProfile->country = Config::get('global.country_id');
        $alias = $userProfile->name . " " . $userProfile->user_id;
        $userProfile->alias = str_slug($alias, "-");
        if ($user->image) {
            $userProfile->photo = $user->image;
        }
        try {
            $userProfile->save();
        } catch (Exception $exe) {
            return LeaseHelper::response(FALSE, 500, $exe->getMessage());
        }
        return LeaseHelper::response(TRUE, 200, "Profile created", $userProfile);
    }

    public static function getExecutive()
    {
        $response = array();
        try {
            $executive = self::select('id AS value', 'name AS text')
                ->filterByRole(Auth::User())
                ->get();
        } catch (Exception $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'successful', $executive);
    }

}
