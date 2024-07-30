<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AutomakerModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
  
    protected $table = 'automakers';
    protected $fillable = ['brand_name','date'];

    public function many()
    {
        return $this->hasMany(RangeOfVehicleModel::class, 'automaker_id', 'id');
    }
}
  