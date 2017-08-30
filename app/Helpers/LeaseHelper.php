<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\User\UserGroup;
use App\Models\User\UserViewLavel;
use Carbon\Carbon;
use Auth;
use JWTAuth;
use Image;
use Config;
use File;
use PhpSpec\Exception\Exception;
use Storage;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\User\User;
use App\Models\User\UserProfile;
use App\Models\Location\Location;
use App\Models\Crm\CategoryMaster;
use App\Models\Crm\ChangeLogTransaction;
use App\Models\Crm\CityMaster;
use App\Models\Crm\ExecutiveMaster;
use App\Models\Crm\ExecutiveUserMap;
use App\Models\Crm\LocationMaster;
use App\Models\Crm\PropertytypeMaster;
use App\Models\Crm\StatusMaster;
use App\Models\Crm\TenantMaster;


class LeaseHelper
{

    public static function getRandomPassword($length = 8)
    {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $base = strlen($salt);
        $makepass = '';

        /*
         * Start with a cryptographic strength random string, then convert it to
         * a string with the numeric base of the salt.
         * Shift the base conversion on each character so the character
         * distribution is even, and randomize the start shift so it's not
         * predictable.
         */
        $random = str_random($length + 1);
        $shift = ord($random[0]);

        for ($i = 1; $i <= $length; ++$i) {
            $makepass .= $salt[($shift + ord($random[$i])) % $base];
            $shift += ord($random[$i]);
        }
        return $makepass;
    }

    public static function getHash($seed)
    {
        return md5(Config::get('key') . $seed);
    }


    private function postSend($url)
    {

//        $request = new \GuzzleHttp\Psr7\Request('POST', $this->apiURL, [
//            'headers' => [
//                'Content-Type' => 'application/json',
//            ],
//            'form_params' => [
//                'longUrl' => $url,
//            ]
//        ]);
//        $promise = $client->postAsync($request)->then(function ($response) {
//            $result = $response->getBody();
//        });
//        $promise->wait();
    }

    public function tinyUrl($url)
    {
        $config = Config::get('global.tinyUrl');
        $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
        $apiURL = $config['url'] . '?key=' . $config['key'];
        $result = $client->post($apiURL, ['json' => ['longUrl' => $url, 'timeout' => 5]]);
        $statusCode = $result->getStatusCode();
        if ($statusCode != 200) {
            $response['success'] = FALSE;
            $response['status_code'] = 200;
            $response['message'] = 'Failed!';
            return $response;
        }
        $response['success'] = true;
        $response['status_code'] = 200;
        $response['data'] = json_decode($result->getBody());
        return $response;
    }

    public static function sentSMS($data)
    {
        $config = Config::get('global.smsGetway.live');
        $client = new \GuzzleHttp\Client();
        $apiURL = $config['url']
            . '?apikey=' . $config['key']
            . '&username=' . $config['username']
            . '&sendername=' . $config['sendername']
            . '&smstype=' . $config['smstype']
            . '&numbers=' . $data['contact']
            . '&message=' . $data['message'];
        $result = $client->request('GET', $apiURL, ['timeout' => 5]);
        $statusCode = $result->getStatusCode();
        if ($statusCode != 200) {
            return self::response(false, 200, 'SMS Failed to send');
        }
        return self::response(true, 200, 'SMS sent successfully', json_decode($result->getBody()));
    }

    public static function scheduleVisit($request)
    {
        $data = [
            'lastname' => $request->input('name'),
            'firstname' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('contact'),
            'label:Visit Date' => Carbon::parse($request->input('datetime'))->format('d-m-Y'),
            'label:Visit Time' => Carbon::parse($request->input('datetime'))->toTimeString(),
            'label:Property RefId' => $request->input('pref'),
            'leadsource' => 'Tenant',
            'name' => 'Tenant-Requirment',
            'leadstatus' => 'Schedule Visit',
            'publicid' => 'b960738f8cb5dccd09ab77e74e103310',
            'callback' => 'JSON_CALLBACK',
        ];
        return self::postCrm($data);
    }


    public static function callBack($request)
    {
        $data = [
            'lastname' => $request->input('name'),
            'firstname' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('contact'),
            'description' => $request->input('message'),
            'label:Property RefId' => $request->input('pref'),
            'leadsource' => 'Callback',
            'name' => 'CallBack',
            'publicid' => 'f9af0948e472ed09aabcf64feca65a80',
            'callback' => 'JSON_CALLBACK',
        ];
        return self::postCrm($data);
    }

    public static function postCrm($data)
    {
        $config = Config::get('global.crm');
        $client = new \GuzzleHttp\Client();
        $apiURL = $config['url'];
        $result = $client->request('POST', $apiURL, ['form_params' => $data]);
        $statusCode = $result->getStatusCode();
        if ($statusCode != 200) {
            $response['success'] = FALSE;
            $response['status_code'] = 200;
            $response['message'] = 'Failed to save';
            return $response;
        }
        $response['success'] = true;
        $response['status_code'] = 200;
        return $response;
    }

    public static function parseMobileOrEmail($request)
    {
        if (is_numeric($request->input('email'))) {
            $field = 'username';
        } elseif (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }
        return $field;
    }

    public static function chackAndCreateUser($data)
    {
        if (!Auth::check()) {
            $userData = [
                'email' => $data['owner_email'],
                'name' => $data['owner_name'],
                'username' => $data['owner_contact'],
            ];
            $user = User::where('email', $userData['email'])->orWhere('username', $userData['username'])->first();
            if ($user) {
                return $user;
            }
            $result = User::store($userData);
            if (!$result['success']) {
                return $result;
            }
            UserProfile::updateProfile($result['user'], Config::get('global.user_profile.type.owner'));
            return $result['user'];
        }
        return Auth::User();
    }

    public static function createToken($user)
    {
        $userInfo = array(
            'em' => $user->email,
            'nam' => $user->name,
            'con' => $user->username,
            /*   'img' => $user->agents->photo*/
        );
        $admin = $user->hasRole(Config('auth.roles.Super_User'), false);
        if ($admin)
            $userInfo['admin'] = $admin;
        /*        $userRole = self::checkUserRole($user);
                if (count($userRole) <= 0)
                    return false;
                $userInfo = array_merge($userInfo, $userRole);*/
//        $userViews = self::viewPermission($user);
//        if ($userViews)
//        $userInfo = array_merge($userInfo, self::viewPermission($user));
        return JWTAuth::fromUser($user, $userInfo);
    }

    public static function checkUserRole($user)
    {
        $role = array();
        $roles = UserGroup::get(['id', 'title'])->toArray();
        foreach ($roles as $key => $value) {
            if ($user->hasRole([Config('auth.roles.Registered'), $value['id']], true))
                $role[strtolower(substr($value['title'], 0, 3))] = true;
            elseif ($value['title'] == Config('auth.roles.Administrator') && $user->hasRole([$value['id']]))
                $role[strtolower(substr($value['title'], 0, 3))] = true;
        }
        $viewRule = UserViewLavel::get(['rules', 'title'])->toArray();
        foreach ($viewRule as $key => $value) {
            if ($user->hasRole(json_decode($value['rules'])))
                $role['perms'][strtolower($value['title'])] = true;
        }
        return $role;
    }

//    public static function viewPermission($user)
//    {
//        $role = array();
//
//        return $role;
//    }

    public static function checkPermission($view, $user, $requireAll)
    {
        $viewRule = UserViewLavel::Where('title', $view)->select('rules')->first();
        if ($viewRule) {
            return $user->hasRole(json_decode($viewRule->rules), $requireAll);
        }
        return false;
    }

    public static function logicalAND($roles, $userRole)
    {
        foreach ($roles as $role) {
            $status = false;
            foreach ($userRole as $key => $value) {
                if ($key == $role) {
                    $status = true;
                    break;
                }
            }
            if ($status == false)
                return false;
        }
        return true;
    }

    public static function logicalOR($roles, $userRole)
    {
        foreach ($roles as $role) {
            foreach ($userRole as $key => $value) {
                if ($key == $role)
                    return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getFormattedTimestamp()
    {
        return str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
    }

    /**
     * @param $timestamp
     * @param $image
     * @return string
     */
    public function getSavedImageName($timestamp, $image)
    {
        return $timestamp . '-' . $image->getClientOriginalName();
    }

    /**
     * @param $image
     * @param $imageFullName
     * @param $storage
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function uploadImage($path, $image)
    {
        return Storage::put($path . '/', $image);
    }

    public function resize($image, $path, $thumbHeight = null, $thumbWidth = null, $mediumHeight = null, $mediumWidth = null)
    {
        try {
            $timestamp = $this->getFormattedTimestamp();
            $imageName = $this->getSavedImageName($timestamp, $image);
            $imageRealPath = $image->getRealPath();
            $img = Image::make($imageRealPath);
            $status = Storage::put($path . '/' . $imageName . '/', $img->stream());
            if ($thumbHeight && $thumbWidth) {
                $thumbPath = $path . '/thumb/' . $imageName . '/';
                $image = Image::make($imageRealPath);
                $image->resize(intval($thumbWidth), intval($thumbHeight), function ($constraint) {
                    $constraint->aspectRatio();
                });
                $status = Storage::put($thumbPath, $image->stream());
            }
            if ($mediumHeight && $mediumWidth) {
                $mediumPath = $path . '/medium/' . $imageName . '/';
                $image = Image::make($imageRealPath);
                $image->resize(intval($mediumWidth), intval($mediumHeight), function ($constraint) {
                    $constraint->aspectRatio();
                });
                $status = Storage::put($mediumPath, $image->stream());
            }
            if (!$status) {
                return $status;
            }
            return $imageName;
            //  return Storage::disk('custom')->put('location/'.$image->getClientOriginalName(), $img->stream());
            //  return $img->save(public_path('images') . '/' . $thumbName);
        } catch (Exception $e) {
            return false;
        }
    }

    public function fit($image, $path, $thumbHeight = null, $thumbWidth = null, $mediumHeight = null, $mediumWidth = null)
    {
        try {
            $timestamp = $this->getFormattedTimestamp();
            $imageName = $this->getSavedImageName($timestamp, $image);
            $imageRealPath = $image->getRealPath();
            $type = $image->getMimeType();
            if ($type == "application/pdf") {
                $status = Storage::put($path . '/'  .$imageName . '/', File::get($image));
//                $status = Storage::put($path . '/' . 'PDF' . '/' .$imageName . '/', File::get($image));
            } else {
                $img = Image::make($imageRealPath);
                $status = Storage::put($path . '/' . $imageName . '/', $img->stream());
                if ($thumbHeight && $thumbWidth) {
                    $thumbPath = $path . '/thumb/' . $imageName . '/';
                    $image = Image::make($imageRealPath);
                    $image->fit(intval($thumbWidth), intval($thumbHeight));
                    $status = Storage::put($thumbPath, $image->stream());
                }
                if ($mediumHeight && $mediumWidth) {
                    $mediumPath = $path . '/medium/' . $imageName . '/';
                    $image = Image::make($imageRealPath);
                    $image->fit(intval($mediumWidth), intval($mediumHeight));
                    $status = Storage::put($mediumPath, $image->stream());
                }
            }
            if (!$status) {
                return false;
            }
            return $imageName;
            //  return Storage::disk('custom')->put('location/'.$image->getClientOriginalName(), $img->stream());
            //  return $img->save(public_path('images') . '/' . $thumbName);
        } catch (Exception $e) {
            return false;
        }
    }

    public function resizeAspectRatioWithHight($image, $path, $id, $height = null)
    {
        try {
            $storage = new Storage();
            $timestamp = $this->getFormattedTimestamp();
            $imageName = $this->getSavedImageName($timestamp, $image);
            $imageRealPath = $image->getRealPath();
            $img = Image::make($imageRealPath);
            $status = $this->uploadImage($path, $img->stream(), $storage);
            if ($height) {
                $img->resize(null, intval($height), function ($constraint) {
                    $constraint->aspectRatio();
                });
                $status = $this->uploadThumbImage($path, $img->stream(), $storage);
            }

            if (!$status) {
                return false;
            }
            return $imageName;
            //  return Storage::disk('custom')->put('location/'.$image->getClientOriginalName(), $img->stream());
            //  return $img->save(public_path('images') . '/' . $thumbName);
        } catch (Exception $e) {
            return false;
        }
    }

    public function resizeAspectRatioWidth($image, $path, $id, $width = null)
    {
        try {
            $storage = new Storage();
            $timestamp = $this->getFormattedTimestamp();
            $imageName = $this->getSavedImageName($timestamp, $image);
            $imageRealPath = $image->getRealPath();
            $img = Image::make($imageRealPath);
            $status = $this->uploadImage($path, $img->stream(), $storage);
            if ($width) {
                $img->resize(intval($width), null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $status = $this->uploadThumbImage($path, $img->stream(), $storage);
            }
            if (!$status) {
                return false;
            }
            return $imageName;
            //  return Storage::disk('custom')->put('location/'.$image->getClientOriginalName(), $img->stream());
            //  return $img->save(public_path('images') . '/' . $thumbName);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function response($success = false, $error_code = 400, $msg = null, $result = null)
    {
        $response['success'] = $success;
        $response['status_code'] = $error_code;
        $response['message'] = $msg;
        $response['data'] = $result;
        return $response;
    }

    public static function parseGeoLocation($data)
    {
        $item = array();
        $location_name = '';
        try {
            if (array_has($data, 'country'))
                $item['country_id'] = Country::store($data['country'])['data'];
            if (array_has($data, 'administrative_area_level_1'))
                $item['state_id'] = State::store(strtoupper($data['administrative_area_level_1']), $item['country_id'])['data'];
            if (array_has($data, 'locality'))
                $item['city_id'] = City::store($data['locality'], $item['state_id'], $item['country_id'])['data'];
            if (array_has($data, 'sublocality_level_1'))
                $location_name = $data['sublocality_level_1'];
            else
                $location_name = array_has($data, 'place') ? ', ' . $data['place'] : $data['locality'];
            if (array_has($data, 'sublocality_level_2'))
                $location_name .= ', ' . $data['sublocality_level_2'];
            $item['location_name'] = $location_name;
            if (array_has($data, 'place_id'))
                $item['place_id'] = $data['place_id'];
            if (array_has($data, 'lat'))
                $item['latitude'] = $data['lat'];
            if (array_has($data, 'lng'))
                $item['longitude'] = $data['lng'];
            if (array_has($data, 'geometry'))
                $item['geometry'] = json_encode($data['geometry']);
            $result = Location::checkLocation($item);
        } catch (ModelNotFoundException $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return $result;
    }

    /*-------------------------------CRM HELPER----------------------------------*/


    public static $constants;

    public static function getExecutive($key)
    {
        $config = Config::get('global.crm');
        $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
        $apiURL = $config['crm_auth_api'];
        try {
            //  $result = $client->request('POST', $apiURL, ['form_params' => ['jwt' => $key]]);
            $result = $client->post($apiURL, ['json' => ['jwt' => $key]]);
        } catch (Exception $ex) {
            $response['success'] = false;
            $response['status_code'] = 200;
            $response['message'] = 'Failed!';
            return $response;
        }
        $statusCode = $result->getStatusCode();
        if ($statusCode != 200) {
            $response['success'] = false;
            $response['status_code'] = 200;
            $response['message'] = 'Failed!';
            return $response;
        }
        $response['success'] = true;
        $response['status_code'] = 200;
        $response['data'] = json_decode($result->getBody());
        return $response;
    }

    public static function prepData($cond = array(), $requirement = null, $getOne = false, $paginate = 0, $orderBy = [], $orWhere = array())
    {

        for ($i = 0; $i < sizeof($cond); $i++) {
            if (sizeof($cond[$i]) == 3) {
                if ($cond[$i][1] == "notIn")
                    $requirement = $requirement->whereNotIn($cond[$i][0], $cond[$i][2]);
                else if ($cond[$i][1] == "In")
                    $requirement = $requirement->whereIn($cond[$i][0], $cond[$i][2]);
                else
                    $requirement = $requirement->where($cond[$i][0], $cond[$i][1], $cond[$i][2]);
            } else if (sizeof($cond[$i]) == 2)
                $requirement = $requirement->where($cond[$i][0], $cond[$i][1]);
        }

        for ($i = 0; $i < sizeof($orWhere); $i++) {
            if (sizeof($cond[$i]) == 3) {
                if ($cond[$i][1] == "notIn")
                    $requirement = $requirement->whereNotIn($cond[$i][0], $cond[$i][2]);
                else if ($cond[$i][1] == "In")
                    $requirement = $requirement->whereIn($cond[$i][0], $cond[$i][2]);
                else
                    $requirement = $requirement->where($cond[$i][0], $cond[$i][1], $cond[$i][2]);
            } else if (sizeof($cond[$i]) == 2)
                $requirement = $requirement->where($cond[$i][0], $cond[$i][1]);
        }

        if (sizeof($orderBy) == 2)
            $requirement->orderBy($orderBy['column'], $orderBy['order']);

        if ($paginate > 0)
            $requirement = $requirement->paginate($paginate);
        else
            $requirement = $requirement->get();

        if ($getOne && isset($requirement))
            $requirement = $requirement->first();
        return $requirement;
    }

    public static function getChanges($data = [], $requirement = null)
    {
        $keys = array();
        $old_val = array();
        $new_val = array();
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $old_val[] = $requirement->$key;
            $new_val[] = $value;
        }
        return [$keys, $old_val, $new_val];
    }

    public static function getConstant()
    {
        LeaseHelper::$constants = config("constants");
    }

    public static function getAuthReqArray()
    {
        return [
            config('constants.KEY_ID') => 'required|integer',
            config('constants.KEY_CLIENT') => 'required',
            config('constants.KEY_SECRET') => 'required',
        ];
    }

    public static function postURL($email, $pwd)
    {
        $curl = curl_init();
        $params = array("email" => $email, "password" => $pwd);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://www.101lease.com/api/v1/auth2/login',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $params
        ));
        $resp = json_decode(curl_exec($curl), true);
        curl_close($curl);
        if ($resp["success"]) {
            $user_id = $resp["user"]["id"];
            $exe_id = ExecutiveUserMap::getExecutive([["id", $user_id]])['details'];
            $response = self::parseJWT($resp["key"]["token"], $exe_id);
        } else {
            $response = array();
        }
        return $response;
    }

    public static function getURL($token)
    {
        $url = "https://www.101lease.com/api/v1/auth2/profile?token=" . $token;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));
        $resp = json_decode(curl_exec($curl), true);
        curl_close($curl);
        if ($resp['success']) {
            $user_id = $resp['userProfile']['user_id'];
            $exe_id = ExecutiveUserMap::getExecutive([['id', $user_id]]);
            if (!$exe_id['success']) {
                return array($token, 0);
            }
            $_SERVER['session']->set('auth.id', $exe_id['details']);
            $_SERVER['session']->set('auth.level1', true);
            $_SERVER['session']->set('auth.level2', true);
            if ($exe_id['success']) {
                $save = ExecutiveMaster::updateData($exe_id['details'], array('jwt' => $token));
                if ($save['success']) {
                    return array($token, $exe_id['details']);
                } else {
                    return array($token, 0);
                }
            } else {
                return array($token, 0);
            }
        } else
            return array($token, 0);


    }

// TODO create a function PINEncrypt, PINmatcher

    public static function logMeIn($var, $client = "deviceID", $id = null, $user_id = null, $lvlpin = 0)
    {
        if (isset($id))
            $_SERVER['session']->set('auth.id', $id);
        $_SERVER['session']->set('auth.level1', false);
        $_SERVER['session']->set('auth.level2', false);
        if (isset($var)) {
            switch ($client) {
                case "deviceID":
                    $resp = self::parseDeviceID($id, $var);
                    break;
                case "jwt":
                    $resp = self::parseJWT($var, $id, $user_id);
                    break;
                default:
                    $resp = self::response(404);
                    break;
            }
        } else
            $resp = self::response(404);
        if ($lvlpin > 0 && $resp[config('constants.KEY_SUCCESS')])
            $resp = self::authUser2($lvlpin);
        return $resp;
    }

// Checks for device_id (for mobile system)
    public static function parseDeviceID($id, $device_id)
    {
        if (($id > 0) && strlen($device_id) > 0) {
            return ExecutiveMaster::authenticateExecutive($id, $device_id);
        } else {
            return self::response(404);
        }
    }

// Validates pin (level 2 security)
    public static function authUser2($pin)
    {
        if ((strlen($pin) > 0))
            return ExecutiveMaster::authenticate2Executive($pin);
        else
            return LeaseHelper::response(404);
    }

    public static function parseRequirement($data)
    {
        $tenant = array();
        $requr = array();
        $city = array();

        if (isset($data['executive']))
            $requr['exe_id'] = $data['executive'];
        if (isset($data['tenant']))
            $requr['tenant_id'] = $data['tenant'];
        else
            $requr['tenant_id'] = self::saveTenantInfo($data)['details'];
        if (isset($data['category']))
            $requr['category'] = $data['category'];
        else
            $requr['category'] = Category::getCategoryId($data['category'])['details'];
        if (isset($data['prop_type_id']))
            $requr['prop_type_id'] = $data['prop_type_id'];
        else
            $requr['prop_type_id'] = Category::getCategoryId($data['prop_type_id'])['details'];
        if (isset($data['requirement_from']))
            $requr['requirement_from'] = $data['requirement_from'];
        if (isset($data['location1_id']))
            $requr['location1_id'] = $data['location1_id'];
        if (isset($data['location1_name']) && !empty($data['location1_name']))
            $requr['location1_id'] = self::parseGeoLocation($data['location1_name'])['details'];
        if (isset($data['location2_id']))
            $requr['location2_id'] = $data['location2_id'];
        if (isset($data['location2_name']) && !empty($data['location2_name']))
            $requr['location2_id'] = self::parseGeoLocation($data['location2_name'])['details'];
        if (isset($data['location3_id']))
            $requr['location3_id'] = $data['location3_id'];
        if (isset($data['location3_name']) && !empty($data['location3_name']))
            $requr['location3_id'] = self::parseGeoLocation($data['location3_name'])['details'];
        if (isset($data['id']) && $data['id'] > 0)
            $requr['id'] = $data['id'];
        if (isset($data['rent_amount']))
            $requr['rent_amount'] = $data['rent_amount'];
        if (isset($data['next_follow_up']))
            $requr['next_follow_up'] = $data['next_follow_up'];
//
        if (isset($data['lead_source']))
            $requr['lead_source'] = $data['lead_source'];
        if (isset($data['status']))
            $requr['status_id'] = $data['status'];
        //  $requr['raw_query'] = $_SERVER['QUERY_STRING'];

//        $requr = self::prepUpdateArray($requr);

        return $requr;
    }

    public static function geoCodePlace($location)
    {
        if (isset($location) && strlen($location) > 0) {
            $url = str_replace(" ", "+", "https://maps.googleapis.com/maps/api/geocode/json?address=" . $location);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
            $geoloc = curl_exec($ch);
            curl_close($ch);
            return LocationMaster::parseGooglePlace(json_decode($geoloc, true), $location);
        } else {
            return null;
        }
    }

    public static function prepUpdateArray($changes = array())
    {
        if (array_has($changes, 'visit_status_id'))
            $changes['visit_status_id'] = LeaseHelper::UpdateStatus($changes['visit_status_id'], 1);

        if (array_has($changes, 'prop_address'))
            $changes['prop_address_id'] = LeaseHelper::geoCodePlace($changes['prop_address'])['details'];

        if (array_has($changes, 'location1_name'))
            $changes['location1_id'] = LeaseHelper::geoCodePlace($changes['location1_name'])['details'];

        if (array_has($changes, 'location2_name'))
            $changes['location2_id'] = LeaseHelper::geoCodePlace($changes['location2_name'])['details'];

        if (array_has($changes, 'location3_name'))
            $changes['location3_id'] = LeaseHelper::geoCodePlace($changes['location3_name'])['details'];

        if (array_has($changes, 'status_id'))
            $changes['status_id'] = LeaseHelper::UpdateStatus($changes['status_id']);

        if (array_has($changes, 'prop_type_id'))
            $changes['prop_type_id'] = Category::getCategoryId($changes['prop_type_id'])['details'];

//        if (array_has($changes, 'city'))
//            $changes['city'] = LeaseHelper::UpdateCity($changes['city']);

        if (array_has($changes, 'category'))
            $changes['category'] = Category::getCategoryId($changes['category'])['details'];
        return $changes;
    }

    public static function UpdateCity($city)
    {
        if (!filter_var($city, FILTER_VALIDATE_INT) && is_array($city) && sizeof($city) >= 3)
            $statusID = CityMaster::addcity($city)['details'];
        else if (filter_var($city, FILTER_VALIDATE_INT))
            $statusID = $city;
        else
            $statusID = 1;
        return $statusID;
    }

    public static function UpdateCategory($cat)
    {
        if (!filter_var($cat, FILTER_VALIDATE_INT) && is_string($cat) && strlen($cat) > 0)
            $statusID = CategoryMaster::addCategory(array('category' => $cat))['details'];
        else if (filter_var($cat, FILTER_VALIDATE_INT))
            $statusID = $cat;
        else
            $statusID = 1;
        return $statusID;
    }

    public static function UpdatePropType($prop)
    {
        if (!filter_var($prop, FILTER_VALIDATE_INT) && is_string($prop) && strlen($prop) > 0)
            $statusID = PropertytypeMaster::propType(array('prop_type' => $prop))['details'];
        else if (filter_var($prop, FILTER_VALIDATE_INT))
            $statusID = $prop;
        else
            $statusID = 1;
        return $statusID;
    }

    public static function UpdateStatus($status, $forVisit = 0)
    {
        if (!filter_var($status, FILTER_VALIDATE_INT) && is_string($status) && strlen($status) > 0)
            $statusID = StatusMaster::addStatus(array('content' => $status, 'forVisit' => $forVisit))['details'];
        else if (filter_var($status, FILTER_VALIDATE_INT))
            $statusID = $status;
        else
            $statusID = 1;
        return $statusID;
    }

    public static function parseJWT($jwt, $exe_id = null, $user_id = null)
    {
        if ($exe_id) {
            $response = ExecutiveMaster::authenticateExecutive($exe_id, $jwt, "jwt");
            $mathed = $response['success'];
            if (!$mathed)
                $response = self::parseJWT($jwt);

        } else if ($user_id) {
            $exe_id_extracted = ExecutiveUserMap::getExecutive([['id', $user_id]]);
            if ($exe_id_extracted > 0)
                $response = self::parseJWT($jwt, $exe_id_extracted);
            else
                $response = LeaseHelper::response(403);
        } else {
            $array = self::getURL($jwt);
            if (isset($array, $array[0], $array[1]) && $array[1] > 0)
                $response = self::parseJWT($array[0], $array[1]);
            else
                $response = LeaseHelper::response(404, null, "URL not found!");
        }
        return $response;
    }

    public static function prefixRemove($id)
    {
        if (substr($id, 0, strlen('R')) == 'R') {
            $id = substr($id, strlen('R'));
        }
        return $id;
    }

    public static function prefixAttach($id)
    {
        if (!substr($id, 0, strlen('R')) == 'R') {
            $id = 'R' . $id;
        }
        return $id;
    }

    public static function hasPermission($exe_id, $level = 1)
    {
        $perm = false;
        switch ($level) {
            case 1:
                if ($_SERVER['session']->get('auth.level1', false)) {
                    $id = $_SERVER['session']->get('auth.id');
                    if ($id == $exe_id)
                        $perm = true;
                }
                break;
            case 2:
                if (self::hasPermission($exe_id, 1) && $_SERVER['session']->get('auth.level2', false)) {
                    $id = $_SERVER['session']->get('auth.id');
                    if ($id == $exe_id)
                        $perm = true;
                }
                break;
        }
        return $perm;
    }

    public static function recordChange($exe_id, $table, $result, $every)
    {
        if (!isset($exe_id) || $exe_id == null || $exe_id <= 0)
            $exe_id = $_SERVER['session']->get('auth.id');

        if ($result['success']) {
            $pkey_val = $result['details'];
            $key = $every[0];
            $old_val = $every[1];
            $new_val = $every[2];
            $count = count($key);
            $resp = array();
            if (isset($exe_id, $table, $pkey_val) && $count == count($old_val) && $count == count($new_val)) {
                for ($i = 0; $i < $count; $i++) {
                    $data = array();
                    $data['exe_id'] = $exe_id;
                    $data['table_name'] = $table;
                    $data['column_pkey_val'] = $pkey_val;
                    $data['column_name'] = $key[$i];
                    if (!isset($old_val[$i]))
                        $old_val[$i] = 0;
                    $data['old_value'] = $old_val[$i];
                    $data['new_value'] = $new_val[$i];
                    $data['create_date'] = Carbon::now();
                    $success = ChangeLogTransaction::logTransactionDetails($data);
                    if (!$success['success'])
                        return false;
                }
                $resp['success'] = true;
                $resp['status_code'] = 200;
                $resp['message'] = "Data updated successfully!";
                return true;
            } else {
                $resp['success'] = false;
                $resp['status_code'] = 200;
                $resp['message'] = "Data cant be updated successfully!";
                return false;
            }
        }
        return false;
    }

//    public
//    static function response($error_code = 400, $details = null, $msg = null)
//    {
//        $message_array = array();
//        $message_array[200] = "";
//        $response = array("status_code" => $error_code);
//        switch ($error_code) {
//            case 200:
//                $response['success'] = true;
//                $response['message'] = 'Ok';
//                break;
//
//            case 203:
//                $response['success'] = true;
//                $response['message'] = 'Non-authoritative Information';
//                break;
//
//            case 400:
//                $response['success'] = false;
//                $response['message'] = 'Bad Request';
//                break;
//
//            case 401:
//                $response['success'] = false;
//                $response['message'] = 'Log IN Error!';
//                break;
//
//            case 403:
//                $response['success'] = false;
//                $response['message'] = 'Permission Denied!';
//                break;
//
//            case 404:
//                $response['success'] = false;
//                $response['message'] = 'Not Found';
//                break;
//
//            case 500:
//                $response['success'] = false;
//                $response['message'] = 'Internal Server Error';
//                break;
//
//            case 503:
//                $response['success'] = false;
//                $response['message'] = 'Service Unavailable';
//                break;
//        }
//        if (isset($response['success']) && $details != null)
//            $response['data'] = $details;
//        if (isset($msg))
//            $response['message'] = $msg;
//        return $response;
//    }


    public static function saveTenantInfo($data)
    {
        try {
            $tenant = User::where('username', $data['mobile'])->orWhere('username', $data['email'])->first();
            if ($tenant == null) {
                $userData = array(
                    'username' => $data['mobile'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['mobile'],
                    'tenant' => true,
                );
                $tenant = User::store($userData, false, true);
                if ($tenant['success']) {
                    $tenant = $tenant['user'];
                }
            }
            $userProfile = UserProfile::where('user_id', $tenant->id)->first();
            if ($userProfile == null) {
                $userProfile = UserProfile::updateProfile($tenant, Config::get('global.user_profile.type.tenant'));
                if ($userProfile['success'])
                    $userProfile = $userProfile['userProfile'];
            }
        } catch (ModelNotFoundException $exe) {
            $response = LeaseHelper::response(500, 1, $exe->getMessage());
            return $response;
        }
        $response = LeaseHelper::response(200, $userProfile->id);
        return $response;
    }
}
