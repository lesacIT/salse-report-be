<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CatDailyActivitiesModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;
//database 
protected $table = 'cat_daily_activities';
    protected $fillable = ['group_id', 'title', 'description'];
    // Define the relationship to CatDailyActivity
    public function belong()
    {
        return $this->belongsTo(CatDailyActivitieGroupsModel::class, 'group_id','id');
    }
}
