<?php

namespace App;

use App\Model\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable 
// implements MustVerifyEmail
{
    use Notifiable;
    use Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rolValidation($roles){
        $rolesAssignated = Role::whereJsonContains('assignation',strval(Auth::id()))->pluck('name')->toArray();
        $validator = array_intersect($roles,$rolesAssignated);
        if($validator){
            return true;
        }else{
            return false;
        }
    }

    public function assignatedRoles($id){
        return Role::whereJsonContains('assignation', strval(decrypt($id)))->pluck('name')->toArray();
    }
}
