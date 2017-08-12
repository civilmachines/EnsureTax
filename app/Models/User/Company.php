<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'osrs_companies';
    protected $fillable = [
        'id', 'company_name', 'company_alias', 'user_id', 'address', 'state', 'city', 'country', 'postcode', 'phone', 'fax',
        'email', 'website', 'photo', 'company_description', 'request_to_approval', 'published'
    ];
    protected $hidden = ['id', 'company_alias', 'user_id', 'address', 'state', 'city', 'country', 'postcode', 'phone', 'fax',
        'email', 'company_description', 'request_to_approval', 'published'];
    public $timestamps = false;

    public function companyAdmin()
    {
        return $this->hasOne('App\Models\User\UserProfile', 'user_id', 'user_id');
    }
}
