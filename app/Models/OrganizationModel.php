<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class OrganizationModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;
    protected $table = 'organization';
    protected $fillable = ['parent_id','code','name','level'];
    public function have()
    {
        return $this->hasMany(User::class,'organization_id' ,'id');
    }
    public function manager()
    {
        return $this->belongsTo(OrganizationModel::class, 'parent_id','id');
    }

    public function managedOrganization()
    {
        return $this->belongsTo(OrganizationModel::class, 'parent_id','id');
    }
}
