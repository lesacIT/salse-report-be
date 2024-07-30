<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class DailyTodoDetailsModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;
    //database
    protected $table = 'daily_todo_details';
    protected $fillable = ['daily_todo_id','time_slot_id','daily_activity_id','place','detail','finished'];
    public function group()
    {
        return $this->belongsTo(DailyTodoModel::class, 'group_id');
    }
}
