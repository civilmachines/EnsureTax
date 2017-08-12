<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserGroup extends Model
{

    protected $table = 'usergroups';
    protected $fillable = [
        'tittle', 'parent_id', 'lft', 'rgt'
    ];
    protected $hidden = [
        'parent_id', 'lft', 'rgt'
    ];

    public function parent()
    {
        return $this->belongsTo(' App\Models\User\UserGroup', 'parent_id');
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public $timestamps = false;


//    public static function updateUserGroup($id) {
//        $data = array();
//        $groupId = 0;
//        try {
//            $userGroup = DB::table('usergroups')
//                    ->where(['title' => 'Registered'])
//                    ->first(['id']);
//        } catch (ModelNotFoundException $exe) {
//            return FALSE;
//        }
//        $data['user_id'] = $id;
//        $data['group_id'] = $userGroup->id;
//        if (UserGroup::create($data)) {
//            return FALSE;
//        }
//        return TRUE;
//    }

//    public function getUserGroup($condition) {
//        try {
//            $userGroup = $this->where([''])all();
//        } catch (Exception $ex) {
//            
//        }
//    }
}
