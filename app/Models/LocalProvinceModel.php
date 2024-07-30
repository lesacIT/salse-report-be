<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class LocalProvinceModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'local_province';
    protected $fillable = [
        'province_name','code'
    ];

    public function districts()
    {
        return $this->hasMany(LocalDistrictModel::class,'province_id');
    }
}

