<?php

namespace App;

use App\Resource as MyResource;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'messenger_uid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        $role = 0;
        switch ($this->role) {
            case 'S':
                $role = 'Student';
                break;
            case 'T':
                $role = 'Teacher';
                break;
            case 'A':
                $role = 'Admin';
                break;
        }
        return $role;
    }


    /**
     * @param string|array $roles
     * @return mixed
     */
    public function authorizeRoles($roles)

    {

        if (is_array($roles)) {

            return $this->hasAnyRole($roles) || abort(401, 'This action is unauthorized.');

        }
        return $this->hasRole($roles) || abort(401, 'This action is unauthorized.');

    }


    /**
     * Check multiple roles
     * @param array $roles
     * @return bool
     */

    public function hasAnyRole($roles)

    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;

    }

    /**
     * Check one role
     * @param string $role
     * @return bool
     */

    public function hasRole($role)

    {

        return $role == $this->role();

    }

    /** Grab resources of this user
     *
     * @return an array of resources by this user
     */
    public function resources()
    {
        $this->hasMany(MyResource::class);
    }

    public function announcements()
    {
        $this->hasMany(Announcement::class);
    }
}
