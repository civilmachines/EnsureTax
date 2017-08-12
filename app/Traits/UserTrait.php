<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/27/2017
 * Time: 11:23 AM
 */

namespace App\Traits;


use Cache;
use Config;

trait UserTrait
{
    //Big block of caching functionality.
    public function cachedRoles()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'auth_roles_for_user_' . $this->$userPrimaryKey;
        return Cache::remember($cacheKey, Config::get('cache.ttl'), function () {
            return $this->roles()->get();
        });
    }

    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function roles()
    {
        return $this->belongsToMany(Config::get('auth.role'), Config::get('auth.role_user_table'), Config::get('auth.user_foreign_key'), Config::get('auth.role_foreign_key'));
    }

    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);
                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                if ($role->id == $name) {
                    return true;
                }
            }
        }

        return false;
    }
}