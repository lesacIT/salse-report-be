<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class LocalDistrictModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'local_district';
    protected $fillable = ['province_id','district_name','code'];
    public function province()
    {
        return $this->belongsTo(LocalProvinceModel::class,'province_id','id');
    }

    public function wards()
    {
        return $this->hasMany(LocalWardModel::class,'district_id');
    }
}
