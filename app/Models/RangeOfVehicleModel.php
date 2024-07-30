<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RangeOfVehicleModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'range_of_vehicle';
    protected $fillable = ['automaker_id','name'];
    public function many()
    {
        return $this->hasMany(CapacityModel::class, 'range_of_vehicle_id', 'id');
    }
    public function belong()
    {
        return $this->belongsTo(AutomakerModel::class,'automaker_id','id');
    }
}
