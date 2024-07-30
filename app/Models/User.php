<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasApiTokens, HasFactory, Notifiable; 
     use HasRoles ;
     use SoftDeletes;
     protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'manager_id',
        'organization_id',
        'role_id',
        'status',
        'username',
        'name',
        'email',
        'password',
        'phone_number',
        'identity_number',
        'avatar',
        'id_local_province',
        'id_local_district',
        'id_local_ward',
        'path',
        'api_token',
        'email_verified_at',
        'date_start_working',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
        public function localProvince()
    {
        return $this->belongsTo(LocalProvinceModel::class, 'id_local_province', 'id');
    }

    public function localDistrict()
    {
        return $this->belongsTo(LocalDistrictModel::class, 'id_local_district', 'id');
    }

    public function belongward()
    {
        return $this->belongsTo(LocalWardModel::class, 'id_local_ward', 'id');
    }
    public function organization()
    {
        return $this->belongsTo(OrganizationModel::class, 'organization_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id','id');
    }

    public function managedUsers()
    {
        return $this->hasMany(User::class, 'manager_id','id');
    }
    public function havedlk(){
        return $this->hasMany(LinkPointListModel::class, 'user_id','id');
    }
    
}
