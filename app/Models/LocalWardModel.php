<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class LocalWardModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'local_ward';
    protected $fillable = ['ward_name','code','district_id'];
    public function district()
    {
        return $this->belongsTo(LocalDistrictModel::class,'district_id','id');
    }
   
}
