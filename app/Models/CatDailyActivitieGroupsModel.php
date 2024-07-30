<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CatDailyActivitieGroupsModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;
 //database Description
 protected $table = 'cat_daily_activitie_groups';
    protected $fillable = ['title', 'description'];

    // Define the relationship to CatDailyActivitieGroup
    public function activities()
    {
        return $this->hasMany(CatDailyActivitiesModel::class, 'group_id','id');
    }
}
