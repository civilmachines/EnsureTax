<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class CompanyAgent extends Model
{
    protected $table = 'osrs_company_agents';
    protected $fillable = [
        'id', 'company_id', 'agent_id'
    ];
    protected $hidden = [];
    public $timestamps = false;
}
